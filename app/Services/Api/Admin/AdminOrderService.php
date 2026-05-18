<?php
namespace App\Services\Api\Admin;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use Exception;
use Illuminate\Support\Facades\DB;

class AdminOrderService
{
    public function approveOrder(int $adminId, int $orderId, array $data): Order
    {
        return DB::transaction(function () use ($adminId, $orderId, $data) {
            $order = Order::query()
                ->where('id', $orderId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($order->status !== 'pending') {
                throw new Exception('Only pending orders can be approved.');
            }

            $oldStatus = $order->status;

            $deliveryCost = (float) $data['delivery_cost'];
            $orderTotal = (float) $order->items_total + $deliveryCost;

            $order->update([
                'status' => 'approved',
                'delivery_cost' => $deliveryCost,
                'order_total' => $orderTotal,
                'approved_at' => now(),
                'approved_by' => $adminId,
                'estimated_delivery_time' => $data['estimated_delivery_time'] ?? null,
            ]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'changed_by' => $adminId,
                'old_status' => $oldStatus,
                'new_status' => 'approved',
                'note' => $data['note'] ?? 'Order approved by admin.',
                'created_at' => now(),
            ]);

            return $order->load(['items', 'customer']);
        });
    }
    public function rejectOrder(int $adminId, int $orderId, array $data): Order
    {
        return DB::transaction(function () use ($adminId, $orderId, $data) {
            $order = Order::query()
                ->where('id', $orderId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($order->status !== 'pending') {
                throw new Exception('Only pending orders can be rejected.');
            }

            $oldStatus = $order->status;

            $order->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejected_by' => $adminId,
                'admin_rejection_reason' => $data['admin_rejection_reason'],
            ]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'changed_by' => $adminId,
                'old_status' => $oldStatus,
                'new_status' => 'rejected',
                'note' => $data['admin_rejection_reason'],
                'created_at' => now(),
            ]);

            return $order->load(['items', 'customer', 'serviceArea']);
        });
    }
}