<?php

declare(strict_types=1);

namespace Modules\Client\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Client\Models\ClientModel;
use Modules\Client\Models\SocialAccountModel;

class SocialAccountFactory extends Factory
{
    protected $model = SocialAccountModel::class;

    public function definition(): array
    {
        return [
            'client_id' => ClientModel::factory(),
            'provider' => 'google',
            'provider_id' => $this->faker->uuid(),
        ];
    }
}
