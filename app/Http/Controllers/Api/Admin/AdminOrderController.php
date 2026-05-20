<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApproveOrderRequest;
use App\Models\Order;
use App\Services\Api\Admin\AdminOrderService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Admin\RejectOrderRequest;
use App\Http\Requests\Admin\RequestOrderRequest;
use App\Http\Requests\Admin\UpdateOrderPaymentRequest;
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
    public function request(RequestOrderRequest $request, Order $order): JsonResponse
    {
        try {
            $order = $this->adminOrderService->requestOrder(
                adminId: $request->user()->id,
                orderId: $order->id,
                note: $request->input('note')
            );

            return response()->json([
                'success' => true,
                'message' => 'Order marked as requested successfully.',
                'data' => $order,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark order as requested.',
                'error' => $e->getMessage(),
            ], 422);
        }
    }
    public function updatePayment(UpdateOrderPaymentRequest $request, Order $order): JsonResponse
    {
        try {
            $updatedOrder = $this->adminOrderService->updatePayment(
                $adminId = $request->user()->id,
                $orderId = $order->id,
                $data = $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'Order payment updated successfully.',
                'data' => $updatedOrder,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order payment.',
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}