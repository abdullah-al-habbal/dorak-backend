<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Modules\Booking\Enums\BookingStatus;
use Modules\Core\Enums\ErrorCodeEnum;
use Modules\Core\Http\Actions\BaseApiAction;

final class DeleteAccountAction extends BaseApiAction
{
    public function __invoke(): JsonResponse
    {
        $data = request()->validate([
            'password' => 'required|string',
        ]);

        $client = request()->user();

        if (! Hash::check($data['password'], $client->password)) {
            return $this->unauthorized(
                message: $this->trans('core::messages.invalid_credentials'),
            );
        }

        $activeBookings = $client->bookings()
            ->whereIn('status', [BookingStatus::Confirmed])
            ->exists();

        if ($activeBookings) {
            return $this->businessError(
                code: ErrorCodeEnum::UNPROCESSABLE_ENTITY,
                message: $this->trans('core::messages.active_bookings_block_deletion'),
            );
        }

        $client->tokens()->delete();

        $client->delete();

        return $this->noContent();
    }
}
