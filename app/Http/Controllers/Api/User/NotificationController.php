<?php
namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
     public function index(Request $request)
    {
        $notifications = EmailLog::query()
            ->whereHas('order', function($q) use ($request) {
                $q->where('customer_id', $request->user()->id);
            })
            ->orderBy('sent_at','desc')
            ->get(['id','order_id','subject','body','sent_at']);

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }
}