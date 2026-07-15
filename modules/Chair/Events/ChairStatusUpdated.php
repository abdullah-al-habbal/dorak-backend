<?php

declare(strict_types=1);

namespace Modules\Chair\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

final class ChairStatusUpdated implements ShouldBroadcast
{
    use SerializesModels;

    public string $chairId;
    public string $branchId;
    public string $status;

    public function __construct(string $chairId, string $branchId, string $status)
    {
        $this->chairId = $chairId;
        $this->branchId = $branchId;
        $this->status = $status;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('floor-plan.' . $this->branchId);
    }

    public function broadcastAs(): string
    {
        return 'chair.status.updated';
    }
}
