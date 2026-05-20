<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = EmailLog::query()
            ->where('user_id', $request->user()->id)
            ->orderBy('sent_at','desc')
            ->get(['id','order_id','subject','body','sent_at']);

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }
}