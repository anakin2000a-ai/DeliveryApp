<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class AutoRejectExpiredOrders extends Command
{
    protected $signature = 'orders:auto-reject-expired';

    protected $description = 'Auto reject expired pending orders';

    public function handle(): int
    {
        $now = now();

        $updated = Order::query()
            ->where('status', 'pending')
            ->where('expires_at', '<=', $now)
            ->update([
                'status' => 'auto_rejected',
                'auto_rejected_at' => $now,
                'rejected_at' => $now,
            ]);

        $this->info("Auto rejected {$updated} orders.");

        return self::SUCCESS;
    }
}