<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    public function getNotifications()
    {
        $user = Auth::user();
        $notifications = Notification::with('document')->where(function ($query) use ($user) {

            $query->where('routed_to', $user->id)

                ->orWhere(function ($sub) use ($user) {
                    $sub->whereNull('routed_to')
                        ->where('destination_office', $user->office->office_code);
                });
        })
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($notifications);
    }
    public function stream()
    {
        $user = auth()->user();
        if (!$user) {
            abort(403);
        }

        return response()->stream(function () use ($user) {
            while (true) {

                $notifications = Notification::where('user_id', $user->id)
                    ->with('document', 'approvals')
                    ->orderBy('created_at', 'desc')
                    ->get(['id', 'document_id', 'message', 'is_read', 'created_at']);

                echo "data: " . json_encode($notifications) . "\n\n";
                ob_flush();
                flush();

                sleep(3);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no'
        ]);
    }


    public function markRead(Request $request)
    {

        $user = Auth::user();
        foreach ($request->ids as $ids) {

            Notification::where('id', $ids)
                ->update([
                    'is_read' => 1,
                ]);
        }

        return response()->json(['success' => true]);
    }
}
