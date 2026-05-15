<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminReplyContactRequest;
use App\Http\Requests\StoreCustomerContactRequest;
use App\Http\Requests\StoreGuestContactRequest;
use App\Models\ContactUs;
use App\Services\Api\ContactUsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ContactUsController extends Controller
{
    public function __construct(
        private readonly ContactUsService $contactUsService
    ) {}

    public function storeGuest(StoreGuestContactRequest $request): JsonResponse
    {
        try {
            $contactUs = $this->contactUsService->createGuest($request->validated());

            return response()->json([
                'message' => 'Message sent successfully.',
                'data' => $contactUs,
            ], 201);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
            'message' => 'Something went wrong while sending the message.',
            'error' => $exception->getMessage(),
        ], 500);
        }
    }

    public function storeCustomer(StoreCustomerContactRequest $request): JsonResponse
    {
        try {
            $contactUs = $this->contactUsService->createCustomer(
                $request->user(),
                $request->validated()
            );

            return response()->json([
                'message' => 'Message sent successfully.',
                'data' => $contactUs,
            ], 201);
        } catch (Throwable $exception) {
            report($exception);

                  return response()->json([
            'message' => 'Something went wrong while sending the message.',
            'error' => $exception->getMessage(),
        ], 500);
        }
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $request->validate([
            'status' => ['nullable', 'in:new,read,replied,closed'],
            'type' => ['nullable', 'in:guest,customer'],
            'search' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

            $messages = $this->contactUsService->paginateForAdmin([
            'status' => $request->query('status'),
            'type' => $request->query('type'),
            'search' => $request->query('search'),
            'start_date' => $request->query('start_date'),
            'end_date' => $request->query('end_date'),
            'per_page' => (int) $request->query('per_page', 15),
        ]);

            return response()->json([
                'message' => 'Messages fetched successfully.',
                'data' => $messages,
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'Something went wrong while fetching messages.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    public function show(ContactUs $contactUs): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Message fetched successfully.',
                'data' => $contactUs->load('user:id,name,email'),
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'Something went wrong while fetching the message.',
            ], 500);
        }
    }

    public function markAsRead(ContactUs $contactUs): JsonResponse
    {
        try {
            $contactUs = $this->contactUsService->markAsRead($contactUs);

            return response()->json([
                'message' => 'Message marked as read.',
                'data' => $contactUs,
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'Something went wrong while updating the message.',
            ], 500);
        }
    }

    public function close(ContactUs $contactUs): JsonResponse
    {
        try {
            $contactUs = $this->contactUsService->close($contactUs);

            return response()->json([
                'message' => 'Message closed successfully.',
                'data' => $contactUs,
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'Something went wrong while closing the message.',
            ], 500);
        }
    }

    public function reply(AdminReplyContactRequest $request, ContactUs $contactUs): JsonResponse
    {
        try {
            $contactUs = $this->contactUsService->reply(
                $contactUs,
                $request->user(),
                $request->validated('reply_message')
            );

            return response()->json([
                'message' => 'Reply saved and email sent successfully.',
                'data' => $contactUs,
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'Something went wrong while sending the reply.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }
    public function customerMessages(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'start_date' => ['nullable', 'date'],
                'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
                'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            ]);

            $messages = $this->contactUsService->paginateForCustomer(
                $request->user(),
                [
                    'start_date' => $request->query('start_date'),
                    'end_date' => $request->query('end_date'),
                    'per_page' => (int) $request->query('per_page', 15),
                ]
            );

            return response()->json([
                'message' => 'Messages fetched successfully.',
                'data' => $messages,
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'Something went wrong while fetching messages.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }
}