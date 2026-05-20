<?php
namespace App\Services\Api\Admin;

use App\Models\Loss;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;

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
    public function requestOrder(int $adminId, int $orderId, ?string $note = null): Order
    {
        return DB::transaction(function () use ($adminId, $orderId, $note) {
            $order = Order::query()
                ->where('id', $orderId)
                ->lockForUpdate()
                ->firstOrFail();

            if (!in_array($order->status, ['pending', 'approved'])) {
                throw new Exception('Only pending or rejected orders can be requested.');
            }

            $oldStatus = $order->status;

            $order->update([
                'status' => 'requested',
                'requested_at' => now(),
            ]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'changed_by' => $adminId,
                'old_status' => $oldStatus,
                'new_status' => 'requested',
                'note' => $note ?? 'Order marked as requested by admin.',
                'created_at' => now(),
            ]);

            return $order->load(['items', 'customer']);
        });
    }
    public function updatePayment(int $adminId, int $orderId, array $data): Order
    {
        return DB::transaction(function () use ($adminId, $orderId, $data) {

            $order = Order::query()
                ->where('id', $orderId)
                ->lockForUpdate()
                ->firstOrFail();

            if (!in_array($order->status, ['approved','requested'])) {
                throw new \Exception('Only approved or requested orders can have payments updated.');
            }

            $note = $data['note'] ?? null;
            $paidAmount = 0;
            $lossAmount = 0;
            $lossReason = null;
            $lossStatus = 'none';
            $paidAt = null;
            $notPaidAt = null;

            if ($data['payment_status'] === 'paid') {
                $paidAmount = (float) $data['paid_amount'];
                $lossAmount = max(0, $order->order_total - $paidAmount);
                $lossReason = $lossAmount > 0 ? $note : null; // general note if there is a loss
                $lossStatus = $lossAmount > 0 ? 'loss' : 'none';
                $paidAt = now();
            } else { // not_paid
                $paidAmount = 0;
                $lossAmount = $order->order_total;
                $lossReason = $note; // always set note for not paid
                $lossStatus = 'loss';
                $notPaidAt = now();
            }

            // Update orders table
            $order->update([
                'payment_status' => $data['payment_status'],
                'paid_amount' => $paidAmount,
                'loss_amount' => $lossAmount,
                'paid_at' => $paidAt,
                'not_paid_at' => $notPaidAt,
                'payment_updated_by' => $adminId,
                'admin_payment_note' => $note,
                'loss_reason' => $lossReason,
                'loss_status' => $lossStatus,
            ]);

            // Update or create Payment row
            \App\Models\Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'updated_by' => $adminId,
                    'payment_status' => $order->payment_status,
                    'amount' => $order->paid_amount,
                    'note' => $note,
                ]
            );

            // Update or create Loss row if there is a loss
            if ($lossAmount > 0) {
                \App\Models\Loss::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'recorded_by' => $adminId,
                        'amount' => $lossAmount,
                        'reason' => $lossReason,
                    ]
                );
            } else {
                // Delete loss row if fully paid
                \App\Models\Loss::where('order_id', $order->id)->delete();
            }

            return $order->refresh();
        });
    }
}