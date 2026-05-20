<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApproveOrderRequest;
use App\Http\Requests\Admin\FilterOrdersRequest;
use App\Http\Requests\Admin\LossReportRequest;
use App\Http\Requests\Admin\PaymentReportRequest;
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
     // List orders with filters
    public function index(FilterOrdersRequest $request): JsonResponse
    {
        try {
            $orders = $this->adminOrderService->listOrders($request->validated());
            return response()->json([
                'success' => true,
                'data' => $orders,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve orders.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Show specific order
    public function show(Order $order): JsonResponse
    {
        try {
            $order = $this->adminOrderService->showOrder($order->id);

            // Limit customer fields to name and location
            $order->customer = [
                'first_name' => $order->customer->first_name,
                'last_name' => $order->customer->last_name,
                'delivery_address' => $order->delivery_address,
                'delivery_latitude' => $order->delivery_latitude,
                'delivery_longitude' => $order->delivery_longitude,
            ];

            return response()->json([
                'success' => true,
                'data' => $order,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

     // Delete a single order
    public function destroy(Order $order): JsonResponse
    {
        try {
            $this->adminOrderService->deleteOrder($order->id);
            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully.'
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete order.',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    // Delete all orders of a given status
    public function destroyAll( string $status): JsonResponse
    {
        try {
            $deletedCount = $this->adminOrderService->deleteOrdersByStatus($status);
            return response()->json([
                'success' => true,
                'message' => "Deleted {$deletedCount} orders with status '{$status}'."
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete orders.',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function payments(PaymentReportRequest $request): JsonResponse
    {
        try {
            $data = $this->adminOrderService->payments($request->validated());
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch payments report.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function losses(LossReportRequest $request): JsonResponse
    {
        try {
            $data = $this->adminOrderService->losses($request->validated());
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch losses report.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}