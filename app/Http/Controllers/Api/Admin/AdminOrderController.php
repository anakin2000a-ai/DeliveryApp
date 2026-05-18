<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApproveOrderRequest;
use App\Models\Order;
use App\Services\Api\Admin\AdminOrderService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Admin\RejectOrderRequest;

use Throwable;

class AdminOrderController extends Controller
{
    public function __construct(
        private readonly AdminOrderService $adminOrderService
    ) {}

    public function approve(ApproveOrderRequest $request, Order $order): JsonResponse
    {
        try {
            $order = $this->adminOrderService->approveOrder(
                adminId: $request->user()->id,
                orderId: $order->id,
                data: $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'Order approved successfully.',
                'data' => $order,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve order.',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    public function reject(RejectOrderRequest $request, Order $order): JsonResponse
    {
        try {
            $order = $this->adminOrderService->rejectOrder(
                adminId: $request->user()->id,
                orderId: $order->id,
                data: $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'Order rejected successfully.',
                'data' => $order,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject order.',
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}