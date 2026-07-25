<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Admin\Models\AdminModel;
use Modules\Ban\Models\BanModel;
use Modules\Barber\Models\BarberModel;
use Modules\BarberAffiliation\Models\BarberAffiliationModel;
use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Models\BookingModel;
use Modules\Branch\Models\BranchModel;
use Modules\Brand\Models\BrandModel;
use Modules\Chair\Models\ChairModel;
use Modules\Client\Mail\SendEmailVerificationCode;
use Modules\Client\Mail\SendPasswordResetCode;
use Modules\Client\Models\ClientModel;
use Modules\Client\Models\SocialAccountModel;
use Modules\Currency\Models\CurrencyModel;
use Modules\Currency\Models\ExchangeRateModel;
use Modules\JobPosting\Models\JobPostingModel;
use Modules\OfferedService\Models\OfferedServiceModel;
use Modules\Review\Models\ReviewModel;

function assertApiEnvelope(mixed $response, int $statusCode, bool $hasData = true): void
{
    $response->assertStatus($statusCode);
    $json = $response->json();
    expect($json)->toHaveKeys(['success', 'statusCode', 'code', 'message', 'timestamp']);
    expect($json['success'])->toBeBool();
    expect($json['statusCode'])->toBeInt();
    expect($json['code'])->toBeString();
    expect($json['message'])->toBeString();
    expect($json['timestamp'])->toBeString();
    if ($hasData) {
        expect($json)->toHaveKey('data');
    }
}

function assertPaginationMeta(mixed $response): void
{
    $json = $response->json();
    expect($json)->toHaveKey('meta');
    expect($json['meta'])->toHaveKey('pagination');
    expect($json['meta']['pagination'])->toHaveKeys(['total', 'count', 'per_page', 'current_page', 'total_pages']);
}

function assertTranslatableField(array $data, string $field): void
{
    expect($data)->toHaveKey($field);
    expect($data[$field])->toBeArray();
    expect($data[$field])->toHaveKeys(['en', 'ar']);
}

// ─────────────────────────────────────────────
// CLIENT MODULE (fills 6 zero-coverage gaps)
// ─────────────────────────────────────────────

describe('Client API', function () {

    describe('POST /api/v1/client/login', function () {
        it('returns correct response shape on success', function () {
            $client = ClientModel::factory()->create([
                'password' => bcrypt('password123'),
            ]);

            $response = $this->postJson('/api/v1/client/login', [
                'email' => $client->email,
                'password' => 'password123',
            ]);

            assertApiEnvelope($response, 200);
            $data = $response->json('data');
            expect($data)->toHaveKeys(['token', 'client']);
            expect($data['token'])->toBeString();
            expect($data['client']['id'])->toBeString();
            expect($data['client']['name'])->toBeString();
            expect($data['client']['email'])->toBeString();
        });

        it('returns 401 with error envelope on invalid credentials', function () {
            $response = $this->postJson('/api/v1/client/login', [
                'email' => 'nonexistent@test.com',
                'password' => 'wrong',
            ]);

            assertApiEnvelope($response, 401, false);
        });
    });

    describe('POST /api/v1/client/register', function () {
        it('returns correct response shape on success', function () {
            $response = $this->postJson('/api/v1/client/register', [
                'name' => 'New Client',
                'email' => 'newclient@test.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

            assertApiEnvelope($response, 201);
            $data = $response->json('data');
            expect($data)->toHaveKeys(['token', 'client']);
            expect($data['token'])->toBeString();
            expect($data['client']['id'])->toBeString();
            expect($data['client']['name'])->toBe('New Client');
            expect($data['client']['email'])->toBe('newclient@test.com');
        });

        it('returns 422 with validation errors', function () {
            $response = $this->postJson('/api/v1/client/register', [
                'name' => '',
                'email' => 'not-an-email',
                'password' => 'short',
            ]);

            $response->assertStatus(422);
            expect($response->json())->toHaveKey('errors');
        });
    });

    describe('POST /api/v1/client/logout', function () {
        it('returns correct response shape on success', function () {
            $client = ClientModel::factory()->create();
            $token = $client->createToken('client-app')->plainTextToken;

            $response = $this->withToken($token)
                ->postJson('/api/v1/client/logout');

            assertApiEnvelope($response, 200, false);
        });

        it('returns 401 without auth', function () {
            $response = $this->postJson('/api/v1/client/logout');
            $response->assertUnauthorized();
        });
    });

    describe('POST /api/v1/client/refresh-token', function () {
        it('returns correct response shape on success', function () {
            $client = ClientModel::factory()->create();
            $token = $client->createToken('client-app')->plainTextToken;

            $response = $this->withToken($token)
                ->postJson('/api/v1/client/refresh-token');

            assertApiEnvelope($response, 200);
            $data = $response->json('data');
            expect($data)->toHaveKey('token');
            expect($data['token'])->toBeString();
        });
    });

    describe('PATCH /api/v1/client/profile', function () {
        it('returns correct response shape on profile update', function () {
            $client = ClientModel::factory()->create();
            $token = $client->createToken('client-app')->plainTextToken;

            $response = $this->withToken($token)
                ->patchJson('/api/v1/client/profile', [
                    'name' => 'Updated Name',
                ]);

            assertApiEnvelope($response, 200);
            $data = $response->json('data');
            expect($data)->toHaveKeys(['id', 'name', 'email', 'phone', 'preferred_universe']);
            expect($data['name'])->toBe('Updated Name');
        });

        it('updates phone on profile update', function () {
            $client = ClientModel::factory()->create();
            $token = $client->createToken('client-app')->plainTextToken;

            $response = $this->withToken($token)
                ->patchJson('/api/v1/client/profile', [
                    'phone' => '+963999999999',
                ]);

            assertApiEnvelope($response, 200);
            expect($response->json('data.phone'))->toBe('+963999999999');
        });
    });

    describe('POST /api/v1/client/avatar', function () {
        it('uploads avatar and returns avatar_url', function () {
            $client = ClientModel::factory()->create();
            $token = $client->createToken('client-app')->plainTextToken;

            $response = $this->withToken($token)
                ->postJson('/api/v1/client/avatar', [
                    'avatar' => UploadedFile::fake()->image('avatar.jpg'),
                ]);

            assertApiEnvelope($response, 200);
            expect($response->json('data'))->toHaveKey('avatar_url');
        });

        it('returns 422 when no file uploaded', function () {
            $client = ClientModel::factory()->create();
            $token = $client->createToken('client-app')->plainTextToken;

            $response = $this->withToken($token)
                ->postJson('/api/v1/client/avatar', []);

            $response->assertStatus(422);
        });

        it('returns 401 without auth', function () {
            $response = $this->postJson('/api/v1/client/avatar', [
                'avatar' => UploadedFile::fake()->image('avatar.jpg'),
            ]);

            $response->assertStatus(401);
        });
    });

    describe('PATCH /api/v1/client/preferences/universe', function () {
        it('returns correct response shape', function () {
            $client = ClientModel::factory()->create();
            $token = $client->createToken('client-app')->plainTextToken;

            $response = $this->withToken($token)
                ->patchJson('/api/v1/client/preferences/universe', [
                    'universe' => 'men',
                ]);

            assertApiEnvelope($response, 200);
            $data = $response->json('data');
            expect($data)->toHaveKey('preferred_universe');
            expect($data['preferred_universe'])->toBe('men');
        });
    });
});

describe('DELETE /api/v1/client/account', function () {
    it('soft-deletes client account with valid password', function () {
        $client = ClientModel::factory()->create(['password' => Hash::make('password123')]);
        $token = $client->createToken('client-app')->plainTextToken;

        $response = $this->withToken($token)
            ->deleteJson('/api/v1/client/account', [
                'password' => 'password123',
            ]);

        assertApiEnvelope($response, 200, false);
        $this->assertSoftDeleted('clients', ['id' => $client->id]);
    });

    it('returns 401 with wrong password', function () {
        $client = ClientModel::factory()->create(['password' => Hash::make('correct')]);
        $token = $client->createToken('client-app')->plainTextToken;

        $response = $this->withToken($token)
            ->deleteJson('/api/v1/client/account', [
                'password' => 'wrong',
            ]);

        $response->assertStatus(401);
        $this->assertNotSoftDeleted('clients', ['id' => $client->id]);
    });

    it('blocks deletion when client has active bookings', function () {
        $client = ClientModel::factory()->create(['password' => Hash::make('password123')]);
        $token = $client->createToken('client-app')->plainTextToken;

        BookingModel::factory()->create([
            'client_id' => $client->id,
            'status' => BookingStatus::Confirmed,
        ]);

        $response = $this->withToken($token)
            ->deleteJson('/api/v1/client/account', [
                'password' => 'password123',
            ]);

        $response->assertStatus(422);
        $this->assertNotSoftDeleted('clients', ['id' => $client->id]);
    });

    it('allows deletion when client has only canceled/completed bookings', function () {
        $client = ClientModel::factory()->create(['password' => Hash::make('password123')]);
        $token = $client->createToken('client-app')->plainTextToken;

        BookingModel::factory()->create([
            'client_id' => $client->id,
            'status' => BookingStatus::Canceled,
        ]);

        $response = $this->withToken($token)
            ->deleteJson('/api/v1/client/account', [
                'password' => 'password123',
            ]);

        assertApiEnvelope($response, 200, false);
        $this->assertSoftDeleted('clients', ['id' => $client->id]);
    });

    it('returns 401 without auth', function () {
        $response = $this->deleteJson('/api/v1/client/account', [
            'password' => 'anything',
        ]);

        $response->assertStatus(401);
    });
});

// ─────────────────────────────────────────────
// CHANGE PASSWORD
// ─────────────────────────────────────────────

describe('Change Password', function () {
    it('changes password with valid current password', function () {
        $client = ClientModel::factory()->create();
        $token = $client->createToken('test')->plainTextToken;
        $oldHash = $client->password;

        $response = $this->withToken($token)
            ->patchJson('/api/v1/client/password', [
                'current_password' => 'password',
                'password' => 'NewPassword123',
                'password_confirmation' => 'NewPassword123',
            ]);

        assertApiEnvelope($response, 200, false);
        $this->assertNotEquals($oldHash, $client->fresh()->password);
        $this->assertCount(0, $client->fresh()->tokens);
    });

    it('returns 422 with wrong current password', function () {
        $client = ClientModel::factory()->create();
        $token = $client->createToken('test')->plainTextToken;

        $response = $this->withToken($token)
            ->patchJson('/api/v1/client/password', [
                'current_password' => 'wrongpassword',
                'password' => 'NewPassword123',
            ]);

        $response->assertStatus(422);
    });

    it('returns 401 without auth', function () {
        $response = $this->patchJson('/api/v1/client/password', [
            'current_password' => 'password',
            'password' => 'NewPassword123',
        ]);

        $response->assertStatus(401);
    });
});

// ─────────────────────────────────────────────
// EMAIL VERIFICATION
// ─────────────────────────────────────────────

describe('Email Verification', function () {
    it('sends verification code to authenticated client', function () {
        $client = ClientModel::factory()->unverified()->create();
        $token = $client->createToken('test')->plainTextToken;

        Mail::fake();

        $response = $this->withToken($token)
            ->postJson('/api/v1/client/email/verify/send');

        assertApiEnvelope($response, 200, false);
        Mail::assertSent(SendEmailVerificationCode::class, fn ($mail) => $mail->hasTo($client->email));
    });

    it('verifies email with correct code', function () {
        $client = ClientModel::factory()->unverified()->create();
        $token = $client->createToken('test')->plainTextToken;

        Cache::put("email_verify_{$client->id}", '123456', now()->addMinutes(10));

        $response = $this->withToken($token)
            ->postJson('/api/v1/client/email/verify', [
                'code' => '123456',
            ]);

        assertApiEnvelope($response, 200, false);
        $this->assertNotNull($client->fresh()->email_verified_at);
    });

    it('returns 422 with wrong code', function () {
        $client = ClientModel::factory()->unverified()->create();
        $token = $client->createToken('test')->plainTextToken;

        Mail::fake();
        $this->withToken($token)->postJson('/api/v1/client/email/verify/send');

        $response = $this->withToken($token)
            ->postJson('/api/v1/client/email/verify', [
                'code' => '000000',
            ]);

        assertApiEnvelope($response, 422, false);
    });

    it('returns 401 without auth', function () {
        $response = $this->postJson('/api/v1/client/email/verify/send');
        $response->assertStatus(401);

        $response = $this->postJson('/api/v1/client/email/verify', ['code' => '123456']);
        $response->assertStatus(401);
    });
});

// ─────────────────────────────────────────────
// FORGOT / RESET PASSWORD
// ─────────────────────────────────────────────

describe('Forgot & Reset Password', function () {
    it('sends reset code for valid email', function () {
        $client = ClientModel::factory()->create();

        Mail::fake();

        $response = $this->postJson('/api/v1/client/forgot-password', [
            'email' => $client->email,
        ]);

        assertApiEnvelope($response, 200, false);
        Mail::assertSent(SendPasswordResetCode::class, fn ($mail) => $mail->hasTo($client->email));
    });

    it('returns 422 for invalid email', function () {
        $response = $this->postJson('/api/v1/client/forgot-password', [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertStatus(422);
    });

    it('resets password with valid code', function () {
        $client = ClientModel::factory()->create();
        $oldHash = $client->password;

        Mail::fake();
        $this->postJson('/api/v1/client/forgot-password', ['email' => $client->email]);

        $code = Cache::get("password_reset_{$client->id}");

        $response = $this->postJson('/api/v1/client/reset-password', [
            'email' => $client->email,
            'code' => $code,
            'password' => 'NewPassword123',
            'password_confirmation' => 'NewPassword123',
        ]);

        assertApiEnvelope($response, 200, false);
        $this->assertNotEquals($oldHash, $client->fresh()->password);
        $this->assertCount(0, $client->fresh()->tokens);
    });

    it('returns 422 with invalid reset code', function () {
        $client = ClientModel::factory()->create();

        Mail::fake();
        $this->postJson('/api/v1/client/forgot-password', ['email' => $client->email]);

        $response = $this->postJson('/api/v1/client/reset-password', [
            'email' => $client->email,
            'code' => '000000',
            'password' => 'NewPassword123',
        ]);

        assertApiEnvelope($response, 422, false);
    });
});

// ─────────────────────────────────────────────
// EXPLORE MODULE (fills Barber Detail gap)
// ─────────────────────────────────────────────

describe('Explore API', function () {

    describe('GET /api/v1/explore/branches', function () {
        it('returns paginated branches with distance', function () {
            $brand = BrandModel::factory()->create();
            $branch = BranchModel::factory()->create([
                'latitude' => 33.5, 'longitude' => 36.3, 'brand_id' => $brand->id,
            ]);

            $response = $this->getJson('/api/v1/explore/branches?latitude=33.5&longitude=36.3&radius=1000&universe=men&per_page=10');

            assertApiEnvelope($response, 200);
            assertPaginationMeta($response);
            $data = $response->json('data');
            expect($data)->toBeArray();
            if (count($data) > 0) {
                expect($data[0])->toHaveKeys(['id', 'name', 'email', 'status', 'latitude', 'longitude', 'brand_id', 'distance', 'compatibility_score', 'rank', 'created_at']);
            }
        });

        it('accepts new filter params and returns correct shape', function () {
            $brand = BrandModel::factory()->create();
            BranchModel::factory()->create([
                'latitude' => 33.5, 'longitude' => 36.3, 'brand_id' => $brand->id,
            ]);

            $response = $this->getJson('/api/v1/explore/branches?latitude=33.5&longitude=36.3&radius=1000&universe=men&available_now=1&rating_min=3&price_range[min]=10&price_range[max]=100');

            assertApiEnvelope($response, 200);
            assertPaginationMeta($response);
            expect($response->json('data'))->toBeArray();
        });
    });

    describe('GET /api/v1/explore/branches/{branch}', function () {
        it('returns branch detail with chairs_count, barbers, services', function () {
            $brand = BrandModel::factory()->create();
            $branch = BranchModel::factory()->create(['brand_id' => $brand->id]);

            $response = $this->getJson("/api/v1/explore/branches/{$branch->id}");

            assertApiEnvelope($response, 200);
            $data = $response->json('data');
            expect($data)->toHaveKeys(['id', 'name', 'chairs_count', 'barbers', 'services', 'created_at']);
            expect($data['barbers'])->toBeArray();
            expect($data['services'])->toBeArray();
        });
    });

    describe('GET /api/v1/explore/barbers', function () {
        it('returns paginated freelancer barbers with distance', function () {
            BarberModel::factory()->create([
                'is_freelancer' => true, 'latitude' => 33.5, 'longitude' => 36.3,
            ]);

            $response = $this->getJson('/api/v1/explore/barbers?latitude=33.5&longitude=36.3&radius=1000');

            assertApiEnvelope($response, 200);
            assertPaginationMeta($response);
            $data = $response->json('data');
            expect($data)->toBeArray();
        });

        it('accepts barber filter params', function () {
            BarberModel::factory()->create([
                'is_freelancer' => true, 'latitude' => 33.5, 'longitude' => 36.3,
            ]);

            $response = $this->getJson('/api/v1/explore/barbers?latitude=33.5&longitude=36.3&radius=1000&available_now=1&rating_min=3');

            assertApiEnvelope($response, 200);
            assertPaginationMeta($response);
            expect($response->json('data'))->toBeArray();
        });
    });

    describe('GET /api/v1/explore/barbers/{barber}', function () {
        it('returns barber detail with services', function () {
            $barber = BarberModel::factory()->create(['is_freelancer' => true]);

            $response = $this->getJson("/api/v1/explore/barbers/{$barber->id}");

            assertApiEnvelope($response, 200);
            $data = $response->json('data');
            expect($data)->toHaveKeys(['id', 'name', 'email', 'is_freelancer', 'status', 'created_at']);
            expect($data['is_freelancer'])->toBeBool();
        });

        it('returns 404 for non-existent barber', function () {
            $response = $this->getJson('/api/v1/explore/barbers/00000000-0000-0000-0000-000000000000');
            $response->assertNotFound();
        });
    });
});

// ─────────────────────────────────────────────
// OFFERED SERVICE MODULE
// ─────────────────────────────────────────────

describe('OfferedService API', function () {

    describe('GET /api/v1/barbers/{barber}/services', function () {
        it('returns barber services with resource shape', function () {
            $barber = BarberModel::factory()->create();
            OfferedServiceModel::factory()->count(2)->create([
                'serviceable_id' => $barber->id,
                'serviceable_type' => 'barber',
            ]);

            $response = $this->getJson("/api/v1/barbers/{$barber->id}/services");

            assertApiEnvelope($response, 200);
            $data = $response->json('data');
            expect($data)->toBeArray();
            expect($data)->toHaveLength(2);
            expect($data[0])->toHaveKeys(['id', 'name', 'description', 'price', 'currency_id', 'duration', 'at_home', 'active', 'created_at']);
        });

        it('returns empty array for barber with no services', function () {
            $barber = BarberModel::factory()->create();

            $response = $this->getJson("/api/v1/barbers/{$barber->id}/services");

            assertApiEnvelope($response, 200);
            expect($response->json('data'))->toBe([]);
        });

        it('returns 404 for non-existent barber', function () {
            $response = $this->getJson('/api/v1/barbers/00000000-0000-0000-0000-000000000000/services');
            $response->assertNotFound();
        });
    });
});

// ─────────────────────────────────────────────
// BAN MODULE
// ─────────────────────────────────────────────

describe('Ban API', function () {

    describe('GET /api/v1/clients/{client}/bans/check', function () {
        it('returns banned=false for client with no bans', function () {
            $client = ClientModel::factory()->create();
            $this->actingAs($client, 'client');

            $response = $this->getJson("/api/v1/clients/{$client->id}/bans/check");

            assertApiEnvelope($response, 200);
            expect($response->json('data.banned'))->toBeFalse();
        });

        it('returns banned=true for actively banned client', function () {
            $client = ClientModel::factory()->create();
            $this->actingAs($client, 'client');
            BanModel::factory()->create([
                'bannable_id' => $client->id,
                'bannable_type' => 'client',
                'banned_from' => now()->subDay(),
                'banned_until' => null,
            ]);

            $response = $this->getJson("/api/v1/clients/{$client->id}/bans/check");

            assertApiEnvelope($response, 200);
            expect($response->json('data.banned'))->toBeTrue();
        });

        it('returns banned=false for expired ban', function () {
            $client = ClientModel::factory()->create();
            $this->actingAs($client, 'client');
            BanModel::factory()->create([
                'bannable_id' => $client->id,
                'bannable_type' => 'client',
                'banned_from' => now()->subDays(10),
                'banned_until' => now()->subDay(),
            ]);

            $response = $this->getJson("/api/v1/clients/{$client->id}/bans/check");

            assertApiEnvelope($response, 200);
            expect($response->json('data.banned'))->toBeFalse();
        });

        it('returns 404 for non-existent client', function () {
            $client = ClientModel::factory()->create();
            $this->actingAs($client, 'client');

            $response = $this->getJson('/api/v1/clients/00000000-0000-0000-0000-000000000000/bans/check');
            $response->assertNotFound();
        });
    });
});

// ─────────────────────────────────────────────
// SOCIAL LOGIN
// ─────────────────────────────────────────────

describe('Social Login API', function () {

    it('registers new client via social token', function () {
        $user = new \Laravel\Socialite\Two\User;
        $user->id = 'google-123';
        $user->name = 'Google User';
        $user->email = 'google@example.com';
        $user->avatar = null;

        $stateless = Mockery::mock();
        $stateless->shouldReceive('userFromToken')->with('valid-token')->andReturn($user);

        $driver = Mockery::mock();
        $driver->shouldReceive('stateless')->andReturn($stateless);

        Laravel\Socialite\Facades\Socialite::shouldReceive('driver')->with('google')->andReturn($driver);

        $response = $this->postJson('/api/v1/client/social/google', [
            'access_token' => 'valid-token',
        ]);

        assertApiEnvelope($response, 200);
        $data = $response->json('data');
        expect($data)->toHaveKeys(['token', 'client']);
        expect($data['client']['email'])->toBe('google@example.com');
    });

    it('returns existing client when social account already linked', function () {
        $client = ClientModel::factory()->create(['email' => 'existing@example.com']);
        SocialAccountModel::factory()->create([
            'client_id' => $client->id,
            'provider' => 'google',
            'provider_id' => 'google-456',
        ]);

        $user = new \Laravel\Socialite\Two\User;
        $user->id = 'google-456';
        $user->name = 'Existing User';
        $user->email = 'existing@example.com';
        $user->avatar = null;

        $stateless = Mockery::mock();
        $stateless->shouldReceive('userFromToken')->with('token-456-google')->andReturn($user);

        $driver = Mockery::mock();
        $driver->shouldReceive('stateless')->andReturn($stateless);

        Laravel\Socialite\Facades\Socialite::shouldReceive('driver')->with('google')->andReturn($driver);

        $response = $this->postJson('/api/v1/client/social/google', [
            'access_token' => 'token-456-google',
        ]);

        assertApiEnvelope($response, 200);
        expect($response->json('data.client.id'))->toBe($client->id);
    });

    it('links new social account to existing client by email', function () {
        $client = ClientModel::factory()->create(['email' => 'same@example.com']);

        $user = new \Laravel\Socialite\Two\User;
        $user->id = 'google-789';
        $user->name = 'Same Email';
        $user->email = 'same@example.com';
        $user->avatar = null;

        $stateless = Mockery::mock();
        $stateless->shouldReceive('userFromToken')->with('token-789-google')->andReturn($user);

        $driver = Mockery::mock();
        $driver->shouldReceive('stateless')->andReturn($stateless);

        Laravel\Socialite\Facades\Socialite::shouldReceive('driver')->with('google')->andReturn($driver);

        $response = $this->postJson('/api/v1/client/social/google', [
            'access_token' => 'token-789-google',
        ]);

        assertApiEnvelope($response, 200);
        expect($response->json('data.client.id'))->toBe($client->id);
        expect($client->socialAccounts()->count())->toBe(1);
    });

    it('returns 422 when access_token is missing', function () {
        $response = $this->postJson('/api/v1/client/social/google', []);
        $response->assertStatus(422);
    });
});

// ─────────────────────────────────────────────
// BOOKING MODULE
// ─────────────────────────────────────────────

describe('Booking API', function () {

    beforeEach(function () {
        $this->client = ClientModel::factory()->create();
        $this->actingAs($this->client, 'client');
    });

    describe('POST /api/v1/bookings', function () {
        it('returns BookingResource shape with nested chair/barber/services', function () {
            $chair = ChairModel::factory()->create();
            $timeSlot = Carbon::now()->addDay()->format('Y-m-d H:i:s');

            $response = $this->postJson('/api/v1/bookings', [
                'chair_id' => $chair->id,
                'time_slot' => $timeSlot,
            ]);

            assertApiEnvelope($response, 201);
            $data = $response->json('data');
            expect($data)->toHaveKeys(['id', 'time_slot', 'status', 'chair', 'services', 'created_at']);
            expect($data['status'])->toBe('confirmed');
            expect($data['chair'])->toHaveKeys(['id', 'label', 'status']);
        });
    });

    describe('GET /api/v1/bookings/{booking}', function () {
        it('returns BookingResource shape', function () {
            $chair = ChairModel::factory()->create();
            $booking = BookingModel::factory()->create([
                'client_id' => $this->client->id,
                'chair_id' => $chair->id,
            ]);

            $response = $this->getJson("/api/v1/bookings/{$booking->id}");

            assertApiEnvelope($response, 200);
            $data = $response->json('data');
            expect($data)->toHaveKeys(['id', 'time_slot', 'status', 'created_at']);
        });
    });

    describe('GET /api/v1/bookings', function () {
        it('returns paginated bookings', function () {
            BookingModel::factory()->count(3)->create([
                'client_id' => $this->client->id,
            ]);

            $response = $this->getJson('/api/v1/bookings?per_page=10');

            assertApiEnvelope($response, 200);
            assertPaginationMeta($response);
            $data = $response->json('data');
            expect($data)->toBeArray();
            expect(count($data))->toBe(3);
        });
    });

    describe('POST /api/v1/client/bookings/{booking}/cancel', function () {
        it('returns BookingResource with status canceled', function () {
            $chair = ChairModel::factory()->create();
            $booking = BookingModel::factory()->create([
                'client_id' => $this->client->id,
                'chair_id' => $chair->id,
                'status' => 'confirmed',
            ]);

            $response = $this->postJson("/api/v1/client/bookings/{$booking->id}/cancel");

            assertApiEnvelope($response, 200);
            $data = $response->json('data');
            expect($data['status'])->toBe('canceled');
        });
    });
});

// ─────────────────────────────────────────────
// REVIEW MODULE
// ─────────────────────────────────────────────

describe('Review API', function () {

    describe('POST /api/v1/client/bookings/{booking}/review', function () {
        it('returns ReviewResource shape', function () {
            $client = ClientModel::factory()->create();
            $this->actingAs($client, 'client');
            $booking = BookingModel::factory()->create([
                'client_id' => $client->id,
                'status' => 'completed',
            ]);

            $response = $this->postJson("/api/v1/client/bookings/{$booking->id}/review", [
                'rating' => 5,
                'comment' => 'Great service!',
            ]);

            assertApiEnvelope($response, 201);
            $data = $response->json('data');
            expect($data)->toHaveKeys(['id', 'rating', 'comment', 'author_name', 'created_at']);
            expect($data['rating'])->toBeInt();
            expect($data['rating'])->toBe(5);
        });
    });

    describe('GET /api/v1/branches/{branch}/reviews', function () {
        it('returns paginated reviews', function () {
            $brand = BrandModel::factory()->create();
            $branch = BranchModel::factory()->create(['brand_id' => $brand->id]);
            ReviewModel::factory()->count(2)->create([
                'subject_id' => $branch->id,
                'subject_type' => BranchModel::class,
            ]);

            $response = $this->getJson("/api/v1/branches/{$branch->id}/reviews");

            assertApiEnvelope($response, 200);
            assertPaginationMeta($response);
            $data = $response->json('data');
            expect($data)->toBeArray();
            if (count($data) > 0) {
                expect($data[0])->toHaveKeys(['id', 'rating', 'comment', 'author_name', 'created_at']);
            }
        });
    });
});

// ─────────────────────────────────────────────
// BRAND MODULE
// ─────────────────────────────────────────────

describe('Brand API', function () {

    describe('GET /api/v1/brands', function () {
        it('returns paginated brands with translatable fields', function () {
            BrandModel::factory()->create();

            $response = $this->getJson('/api/v1/brands');

            assertApiEnvelope($response, 200);
            assertPaginationMeta($response);
            $data = $response->json('data');
            expect($data)->toBeArray();
            if (count($data) > 0) {
                assertTranslatableField($data[0], 'name');
            }
        });
    });

    describe('GET /api/v1/brands/{brand}', function () {
        it('returns brand with nested owner and base_currency', function () {
            $client = ClientModel::factory()->create();
            $currency = CurrencyModel::factory()->create();
            $brand = BrandModel::factory()->create([
                'owner_id' => $client->id,
                'base_currency_id' => $currency->id,
            ]);

            $response = $this->getJson("/api/v1/brands/{$brand->id}");

            assertApiEnvelope($response, 200);
            $data = $response->json('data');
            expect($data)->toHaveKeys(['id', 'name', 'owner', 'base_currency', 'created_at']);
            expect($data['owner'])->toHaveKeys(['id', 'email']);
            expect($data['base_currency'])->toHaveKeys(['id', 'code']);
        });
    });

    describe('POST /api/v1/brands', function () {
        it('returns brand resource with 201', function () {
            $client = ClientModel::factory()->create();
            $this->actingAs($client, 'client');
            $currency = CurrencyModel::factory()->create();

            $response = $this->postJson('/api/v1/brands', [
                'name' => ['en' => 'Test Brand', 'ar' => 'ماركة اختبار'],
                'description' => ['en' => 'Description', 'ar' => 'وصف'],
                'owner_id' => $client->id,
                'base_currency_id' => $currency->id,
            ]);

            assertApiEnvelope($response, 201);
            $data = $response->json('data');
            assertTranslatableField($data, 'name');
            expect($data['name']['en'])->toBe('Test Brand');
        });
    });

    describe('PUT /api/v1/brands/{brand}', function () {
        it('returns updated brand resource', function () {
            $client = ClientModel::factory()->create();
            $this->actingAs($client, 'client');
            $brand = BrandModel::factory()->create(['owner_id' => $client->id]);

            $response = $this->putJson("/api/v1/brands/{$brand->id}", [
                'name' => ['en' => 'Updated Brand'],
            ]);

            assertApiEnvelope($response, 200);
            $data = $response->json('data');
            expect($data['name']['en'])->toBe('Updated Brand');
        });
    });
});

// ─────────────────────────────────────────────
// BARBER AFFILIATION MODULE
// ─────────────────────────────────────────────

describe('BarberAffiliation API', function () {

    beforeEach(function () {
        $this->barber = BarberModel::factory()->create();
        $this->actingAs($this->barber, 'barber');
    });

    describe('POST /api/v1/barbers/{barber}/affiliate', function () {
        it('returns AffiliationResource shape', function () {
            $branch = BranchModel::factory()->create();

            $response = $this->postJson("/api/v1/barbers/{$this->barber->id}/affiliate", [
                'affiliable_id' => $branch->id,
                'affiliable_type' => 'branch',
            ]);

            assertApiEnvelope($response, 201);
            $data = $response->json('data');
            expect($data)->toHaveKeys(['id', 'barber_id', 'affiliable_id', 'affiliable_type', 'status', 'commission_rate', 'created_at']);
            expect($data['status'])->toBe('pending');
        });
    });

    describe('POST /api/v1/affiliations/{affiliation}/accept', function () {
        it('returns AffiliationResource with accepted status', function () {
            $branch = BranchModel::factory()->create();
            $affiliation = BarberAffiliationModel::factory()->create([
                'barber_id' => $this->barber->id,
                'status' => 'pending',
            ]);

            $response = $this->postJson("/api/v1/affiliations/{$affiliation->id}/accept");

            assertApiEnvelope($response, 200);
            $data = $response->json('data');
            expect($data['status'])->toBe('accepted');
            expect($data)->toHaveKey('accepted_at');
        });
    });

    describe('POST /api/v1/affiliations/{affiliation}/reject', function () {
        it('returns AffiliationResource with rejected status', function () {
            $branch = BranchModel::factory()->create();
            $affiliation = BarberAffiliationModel::factory()->create([
                'barber_id' => $this->barber->id,
                'status' => 'pending',
            ]);

            $response = $this->postJson("/api/v1/affiliations/{$affiliation->id}/reject");

            assertApiEnvelope($response, 200);
            $data = $response->json('data');
            expect($data['status'])->toBe('rejected');
        });
    });

    describe('GET /api/v1/barbers/{barber}/affiliations', function () {
        it('returns array of affiliations', function () {
            BarberAffiliationModel::factory()->count(2)->create([
                'barber_id' => $this->barber->id,
            ]);

            $response = $this->getJson("/api/v1/barbers/{$this->barber->id}/affiliations");

            assertApiEnvelope($response, 200);
            $data = $response->json('data');
            expect($data)->toBeArray();
            if (count($data) > 0) {
                expect($data[0])->toHaveKeys(['id', 'barber_id', 'status', 'created_at']);
            }
        });
    });
});

// ─────────────────────────────────────────────
// JOB POSTING MODULE
// ─────────────────────────────────────────────

describe('JobPosting API', function () {

    describe('GET /api/v1/jobs', function () {
        it('returns paginated jobs with translatable fields', function () {
            $branch = BranchModel::factory()->create();
            JobPostingModel::factory()->create([
                'branch_id' => $branch->id,
                'status' => 'open',
            ]);

            $response = $this->getJson('/api/v1/jobs');

            assertApiEnvelope($response, 200);
            assertPaginationMeta($response);
            $data = $response->json('data');
            expect($data)->toBeArray();
            if (count($data) > 0) {
                assertTranslatableField($data[0], 'title');
                assertTranslatableField($data[0], 'description');
                expect($data[0])->toHaveKey('applications_count');
                expect($data[0])->toHaveKey('location');
                expect($data[0])->toHaveKey('type');
            }
        });
    });

    describe('GET /api/v1/jobs/{job}', function () {
        it('returns job detail', function () {
            $job = JobPostingModel::factory()->create(['status' => 'open']);

            $response = $this->getJson("/api/v1/jobs/{$job->id}");

            assertApiEnvelope($response, 200);
            $data = $response->json('data');
            assertTranslatableField($data, 'title');
            assertTranslatableField($data, 'description');
            expect($data['status'])->toBe('open');
        });
    });

    describe('POST /api/v1/jobs/{job}/apply', function () {
        it('returns ApplicationResource shape', function () {
            $barber = BarberModel::factory()->create();
            $this->actingAs($barber, 'barber');
            $job = JobPostingModel::factory()->create(['status' => 'open']);

            $response = $this->postJson("/api/v1/jobs/{$job->id}/apply");

            assertApiEnvelope($response, 201);
            $data = $response->json('data');
            expect($data)->toHaveKeys(['id', 'job_posting_id', 'barber_id', 'profile_snapshot', 'status', 'created_at']);
            expect($data['status'])->toBe('submitted');
            expect($data['profile_snapshot'])->toHaveKeys(['name', 'email', 'is_freelancer']);
        });
    });
});

// ─────────────────────────────────────────────
// CHAIR MODULE
// ─────────────────────────────────────────────

describe('Chair API', function () {

    describe('GET /api/v1/chairs', function () {
        it('returns paginated chairs with barber nested', function () {
            $chair = ChairModel::factory()->create();

            $response = $this->getJson('/api/v1/chairs');

            assertApiEnvelope($response, 200);
            assertPaginationMeta($response);
            $data = $response->json('data');
            expect($data)->toBeArray();
            if (count($data) > 0) {
                expect($data[0])->toHaveKeys(['id', 'label', 'status', 'branch_id', 'created_at']);
            }
        });
    });

    describe('GET /api/v1/chairs/{chair}', function () {
        it('returns chair detail', function () {
            $chair = ChairModel::factory()->create();

            $response = $this->getJson("/api/v1/chairs/{$chair->id}");

            assertApiEnvelope($response, 200);
            $data = $response->json('data');
            expect($data)->toHaveKeys(['id', 'label', 'status', 'branch_id', 'created_at']);
        });
    });

    describe('PATCH /api/v1/chairs/{chair}', function () {
        it('returns updated chair', function () {
            $client = ClientModel::factory()->create();
            $this->actingAs($client, 'client');
            $chair = ChairModel::factory()->create();

            $response = $this->patchJson("/api/v1/chairs/{$chair->id}", [
                'label' => 'Chair A1',
            ]);

            assertApiEnvelope($response, 200);
            $data = $response->json('data');
            expect($data['label'])->toBe('Chair A1');
        });
    });

    describe('GET /api/v1/branches/{branch}/chairs', function () {
        it('returns chairs filtered by branch', function () {
            $branch = BranchModel::factory()->create();
            ChairModel::factory()->count(2)->create(['branch_id' => $branch->id]);

            $response = $this->getJson("/api/v1/branches/{$branch->id}/chairs");

            assertApiEnvelope($response, 200);
            assertPaginationMeta($response);
            $data = $response->json('data');
            expect(count($data))->toBe(2);
        });
    });
});

// ─────────────────────────────────────────────
// CURRENCY MODULE
// ─────────────────────────────────────────────

describe('Currency API', function () {

    describe('GET /api/v1/currencies', function () {
        it('returns list of currencies', function () {
            CurrencyModel::factory()->create(['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$']);

            $response = $this->getJson('/api/v1/currencies');

            assertApiEnvelope($response, 200);
            $data = $response->json('data');
            expect($data)->toBeArray();
            if (count($data) > 0) {
                expect($data[0])->toHaveKeys(['id', 'code', 'name', 'symbol', 'is_default']);
                expect($data[0]['is_default'])->toBeBool();
            }
        });
    });

    describe('GET /api/v1/exchange-rates', function () {
        it('returns list of exchange rates', function () {
            $admin = AdminModel::factory()->create();
            $this->actingAs($admin, 'admin');

            $from = CurrencyModel::factory()->create(['code' => 'USD']);
            $to = CurrencyModel::factory()->create(['code' => 'EUR']);
            ExchangeRateModel::factory()->create([
                'from_currency_id' => $from->id,
                'to_currency_id' => $to->id,
                'rate' => 0.85,
            ]);

            $response = $this->getJson('/api/v1/exchange-rates');

            assertApiEnvelope($response, 200);
            $data = $response->json('data');
            expect($data)->toBeArray();
            if (count($data) > 0) {
                expect($data[0])->toHaveKeys(['id', 'from_currency_id', 'to_currency_id', 'rate']);
                expect($data[0]['rate'])->toBeNumeric();
            }
        });
    });

    describe('POST /api/v1/currency/convert', function () {
        it('returns conversion result shape', function () {
            $admin = AdminModel::factory()->create();
            $this->actingAs($admin, 'admin');

            $from = CurrencyModel::factory()->create(['code' => 'USD']);
            $to = CurrencyModel::factory()->create(['code' => 'EUR']);
            ExchangeRateModel::factory()->create([
                'from_currency_id' => $from->id,
                'to_currency_id' => $to->id,
                'rate' => 0.85,
            ]);

            $response = $this->postJson('/api/v1/currency/convert', [
                'from' => 'USD',
                'to' => 'EUR',
                'amount' => 100,
            ]);

            assertApiEnvelope($response, 200);
            $data = $response->json('data');
            expect($data)->toHaveKeys(['from', 'to', 'amount', 'result', 'rate']);
            expect($data['from'])->toBe('USD');
            expect($data['to'])->toBe('EUR');
            expect((float) $data['amount'])->toBe(100.0);
            expect((float) $data['result'])->toBe(85.0);
        });
    });
});

// ─────────────────────────────────────────────
// ACTIVATION MODULE
// ─────────────────────────────────────────────

describe('Activation API', function () {

    describe('POST /api/v1/barbers/{barber}/activate', function () {
        it('returns ActivationLogResource shape', function () {
            $barber = BarberModel::factory()->create();
            $this->actingAs($barber, 'barber');

            $response = $this->postJson("/api/v1/barbers/{$barber->id}/activate", [
                'reason' => 'Approved after review',
            ]);

            assertApiEnvelope($response, 201);
            $data = $response->json('data');
            expect($data)->toHaveKeys(['id', 'activable_id', 'activable_type', 'status', 'reason', 'activated_at', 'created_at']);
            expect($data['status'])->toBe('enabled');
            expect($data['reason'])->toBe('Approved after review');
        });
    });

    describe('POST /api/v1/barbers/{barber}/deactivate', function () {
        it('returns ActivationLogResource with disabled status', function () {
            $barber = BarberModel::factory()->create();
            $this->actingAs($barber, 'barber');

            $response = $this->postJson("/api/v1/barbers/{$barber->id}/deactivate", [
                'reason' => 'Violation of terms',
            ]);

            assertApiEnvelope($response, 201);
            $data = $response->json('data');
            expect($data['status'])->toBe('disabled');
            expect($data['reason'])->toBe('Violation of terms');
        });
    });
});

// ─────────────────────────────────────────────
// CLIENT HISTORY MODULE (Phase 2)
// ─────────────────────────────────────────────

describe('ClientHistory API', function () {

    function createHistoryEntry(): array
    {
        $client = ClientModel::factory()->create();
        $barber = BarberModel::factory()->create();
        $booking = BookingModel::factory()->create([
            'client_id' => $client->id,
            'barber_id' => $barber->id,
            'status' => BookingStatus::Confirmed,
        ]);

        $booking->update(['status' => BookingStatus::Completed]);

        return ['client' => $client, 'barber' => $barber, 'booking' => $booking];
    }

    describe('GET /api/v1/client/history', function () {
        it('returns paginated history timeline', function () {
            $ctx = createHistoryEntry();

            $response = $this->actingAs($ctx['client'], 'client')
                ->getJson('/api/v1/client/history');

            assertApiEnvelope($response, 200);
            assertPaginationMeta($response);
            $data = $response->json('data');
            expect($data)->toBeArray();
            expect($data)->not->toBeEmpty();
        });

        it('returns 401 without auth', function () {
            $response = $this->getJson('/api/v1/client/history');
            expect($response->status())->toBe(401);
        });
    });

    describe('POST /api/v1/client/history/{history}/media', function () {
        it('attaches media to history entry', function () {
            $ctx = createHistoryEntry();

            $response = $this->actingAs($ctx['client'], 'client')
                ->getJson('/api/v1/client/history');

            $historyId = $response->json('data.0.id');

            $mediaResponse = $this->actingAs($ctx['client'], 'client')
                ->postJson("/api/v1/client/history/{$historyId}/media", [
                    'photo_url' => 'https://example.com/photo.jpg',
                    'photo_type' => 'after',
                ]);

            assertApiEnvelope($mediaResponse, 201);
            $data = $mediaResponse->json('data');
            expect($data)->toHaveKeys(['id', 'photo_url', 'photo_type', 'uploaded_at']);
            expect($data['photo_type'])->toBe('after');
        });

        it('returns 401 without auth', function () {
            $response = $this->postJson('/api/v1/client/history/some-id/media', [
                'photo_url' => 'https://example.com/photo.jpg',
                'photo_type' => 'after',
            ]);
            expect($response->status())->toBe(401);
        });
    });

    describe('POST /api/v1/client/history/{history}/rebook', function () {
        it('creates booking from history entry', function () {
            $ctx = createHistoryEntry();

            $response = $this->actingAs($ctx['client'], 'client')
                ->getJson('/api/v1/client/history');

            $historyId = $response->json('data.0.id');
            $futureSlot = Carbon::now()->addDay()->toIso8601String();

            $rebookResponse = $this->actingAs($ctx['client'], 'client')
                ->postJson("/api/v1/client/history/{$historyId}/rebook", [
                    'time_slot' => $futureSlot,
                ]);

            assertApiEnvelope($rebookResponse, 201);
            $data = $rebookResponse->json('data');
            expect($data)->toHaveKeys(['id', 'time_slot', 'status', 'barber_id']);
            expect($data['status'])->toBe('confirmed');
        });

        it('returns 401 without auth', function () {
            $response = $this->postJson('/api/v1/client/history/some-id/rebook', [
                'time_slot' => Carbon::now()->addDay()->toIso8601String(),
            ]);
            expect($response->status())->toBe(401);
        });
    });
});

// ─────────────────────────────────────────────
// CLIENT FACE PROFILE MODULE (Phase 3)
// ─────────────────────────────────────────────

describe('ClientFaceProfile API', function () {

    describe('POST /api/v1/client/face-profile', function () {
        it('uploads face photo and returns profile', function () {
            $client = ClientModel::factory()->create();
            $file = UploadedFile::fake()->image('face.jpg', 400, 400);

            $response = $this->actingAs($client, 'client')
                ->postJson('/api/v1/client/face-profile', [
                    'photo' => $file,
                    'is_primary' => true,
                ]);

            assertApiEnvelope($response, 201);
            $data = $response->json('data');
            expect($data)->toHaveKeys(['id', 'image_url', 'is_primary', 'uploaded_at']);
            expect($data['is_primary'])->toBeTrue();
        });

        it('returns 422 without photo', function () {
            $client = ClientModel::factory()->create();

            $response = $this->actingAs($client, 'client')
                ->postJson('/api/v1/client/face-profile', []);

            expect($response->status())->toBe(422);
        });

        it('returns 401 without auth', function () {
            $response = $this->postJson('/api/v1/client/face-profile', []);
            expect($response->status())->toBe(401);
        });
    });

    describe('GET /api/v1/client/face-profile/recommendations', function () {
        it('returns analysis results', function () {
            $client = ClientModel::factory()->create();

            $response = $this->actingAs($client, 'client')
                ->getJson('/api/v1/client/face-profile/recommendations');

            assertApiEnvelope($response, 200);
            $data = $response->json('data');
            expect($data)->toBeArray();
        });

        it('returns 401 without auth', function () {
            $response = $this->getJson('/api/v1/client/face-profile/recommendations');
            expect($response->status())->toBe(401);
        });
    });
});

// ─────────────────────────────────────────────
// CLIENT INTERACTION MODULE (Phase 4)
// ─────────────────────────────────────────────

describe('ClientInteraction API', function () {

    describe('Favorites', function () {
        it('lists empty favorites', function () {
            $client = ClientModel::factory()->create();

            $response = $this->actingAs($client, 'client')
                ->getJson('/api/v1/client/favorites?per_page=20');

            assertApiEnvelope($response, 200);
            assertPaginationMeta($response);
            expect($response->json('data'))->toBeArray();
        });

        it('adds a favorite', function () {
            $client = ClientModel::factory()->create();
            $brand = BrandModel::factory()->create();

            $response = $this->actingAs($client, 'client')
                ->postJson('/api/v1/client/favorites', [
                    'favorable_id' => $brand->id,
                    'favorable_type' => 'brand',
                ]);

            expect($response->status())->toBe(201);
            $data = $response->json('data');
            expect($data)->toHaveKeys(['id', 'favorable_id', 'favorable_type', 'created_at']);
            expect($data['favorable_type'])->toBe('brand');
        });

        it('returns 422 for invalid favorable_type', function () {
            $client = ClientModel::factory()->create();

            $response = $this->actingAs($client, 'client')
                ->postJson('/api/v1/client/favorites', [
                    'favorable_id' => 'some-id',
                    'favorable_type' => 'invalid_type',
                ]);

            expect($response->status())->toBe(422);
        });

        it('removes a favorite', function () {
            $client = ClientModel::factory()->create();
            $brand = BrandModel::factory()->create();
            $fav = $this->actingAs($client, 'client')
                ->postJson('/api/v1/client/favorites', [
                    'favorable_id' => $brand->id,
                    'favorable_type' => 'brand',
                ])->json('data');

            $response = $this->actingAs($client, 'client')
                ->deleteJson("/api/v1/client/favorites/{$fav['id']}");

            assertApiEnvelope($response, 200);
        });

        it('returns 401 without auth', function () {
            $response = $this->getJson('/api/v1/client/favorites');
            expect($response->status())->toBe(401);
        });
    });

    describe('Saved Filters', function () {
        it('creates and lists saved filters', function () {
            $client = ClientModel::factory()->create();

            $createResponse = $this->actingAs($client, 'client')
                ->postJson('/api/v1/client/saved-filters', [
                    'name' => 'Nearby Men',
                    'filter_config' => ['universe' => 'men', 'radius' => 10],
                ]);

            expect($createResponse->status())->toBe(201);
            expect($createResponse->json('data'))->toHaveKeys(['id', 'name', 'filter_config']);

            $listResponse = $this->actingAs($client, 'client')
                ->getJson('/api/v1/client/saved-filters');

            assertApiEnvelope($listResponse, 200);
            expect($listResponse->json('data'))->toBeArray();
        });

        it('shows a saved filter', function () {
            $client = ClientModel::factory()->create();
            $created = $this->actingAs($client, 'client')
                ->postJson('/api/v1/client/saved-filters', [
                    'name' => 'Test Filter',
                    'filter_config' => ['universe' => 'women'],
                ])->json('data');

            $response = $this->actingAs($client, 'client')
                ->getJson("/api/v1/client/saved-filters/{$created['id']}");

            assertApiEnvelope($response, 200);
            expect($response->json('data.name'))->toBe('Test Filter');
        });

        it('updates a saved filter', function () {
            $client = ClientModel::factory()->create();
            $created = $this->actingAs($client, 'client')
                ->postJson('/api/v1/client/saved-filters', [
                    'name' => 'Original',
                    'filter_config' => ['universe' => 'men'],
                ])->json('data');

            $response = $this->actingAs($client, 'client')
                ->putJson("/api/v1/client/saved-filters/{$created['id']}", [
                    'name' => 'Updated',
                    'filter_config' => ['universe' => 'women'],
                ]);

            assertApiEnvelope($response, 200);
            expect($response->json('data.name'))->toBe('Updated');
        });

        it('deletes a saved filter', function () {
            $client = ClientModel::factory()->create();
            $created = $this->actingAs($client, 'client')
                ->postJson('/api/v1/client/saved-filters', [
                    'name' => 'Delete Me',
                    'filter_config' => ['universe' => 'neutral'],
                ])->json('data');

            $response = $this->actingAs($client, 'client')
                ->deleteJson("/api/v1/client/saved-filters/{$created['id']}");

            assertApiEnvelope($response, 200);
        });

        it('returns 422 without name', function () {
            $client = ClientModel::factory()->create();

            $response = $this->actingAs($client, 'client')
                ->postJson('/api/v1/client/saved-filters', [
                    'filter_config' => ['universe' => 'men'],
                ]);

            expect($response->status())->toBe(422);
        });

        it('returns 401 without auth', function () {
            $response = $this->getJson('/api/v1/client/saved-filters');
            expect($response->status())->toBe(401);
        });
    });

    describe('Discovery Preferences', function () {
        it('returns default preferences', function () {
            $client = ClientModel::factory()->create();

            $response = $this->actingAs($client, 'client')
                ->getJson('/api/v1/client/discovery-preferences');

            assertApiEnvelope($response, 200);
            $data = $response->json('data');
            expect($data)->toHaveKeys(['id', 'preferred_universe', 'default_radius', 'hidden_brand_ids', 'show_unavailable']);
            expect($data['default_radius'])->toBe(50);
            expect($data['show_unavailable'])->toBeTrue();
        });

        it('updates discovery preferences', function () {
            $client = ClientModel::factory()->create();

            $response = $this->actingAs($client, 'client')
                ->patchJson('/api/v1/client/discovery-preferences', [
                    'default_radius' => 25,
                    'show_unavailable' => false,
                ]);

            assertApiEnvelope($response, 200);
            expect($response->json('data.default_radius'))->toEqual(25.0);
            expect($response->json('data.show_unavailable'))->toBeFalse();
        });

        it('returns 401 without auth', function () {
            $response = $this->getJson('/api/v1/client/discovery-preferences');
            expect($response->status())->toBe(401);
        });
    });
});
