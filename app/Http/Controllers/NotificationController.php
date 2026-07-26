<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    const PAGE_SIZE = 15;

    public function index(Request $request)
    {
        $query = Notification::with('sender:id,name')
            ->where('user_id', Auth::id())
            ->orderBy('id', 'desc');

        if ($beforeId = $request->integer('before_id')) {
            $query->where('id', '<', $beforeId);
        }

        $notifications = $query->limit(self::PAGE_SIZE)
            ->get([
                'id',
                'type',
                'title',
                'message',
                'link_title',
                'link_url',
                'data',
                'is_read',
                'from_user_id',
                'created_at',
            ]);

        return response()->json([
            'data' => $notifications,
            'has_more' => $notifications->count() === self::PAGE_SIZE,
        ]);
    }

    public function unreadCount()
    {
        $count = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function markRead(Request $request)
    {
        $query = Notification::where('user_id', Auth::id());

        if ($request->boolean('all')) {
            $query->update(['is_read' => true]);

            return response()->json(['success' => true]);
        }

        $ids = $request->input('ids', []);
        $query->whereIn('id', $ids)->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function testSend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string'],
            'message' => ['required', 'string'],
            'type' => ['nullable', 'string'],
            'link.title' => ['nullable', 'string'],
            'link.url' => ['nullable', 'string'],
            'target.type' => ['required', 'in:user,role,department'],
            'target.user_id' => ['required_if:target.type,user', 'integer'],
            'target.role' => ['required_if:target.type,role', 'string'],
            'target.department_id' => ['required_if:target.type,department', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input detected.',
                'invalid_fields' => $validator->errors(),
            ], 422);
        }

        $notifications = NotificationService::send([
            'type' => $request->input('type'),
            'title' => $request->input('title'),
            'message' => $request->input('message'),
            'from_user_id' => Auth::id(),
            'link' => $request->input('link', []),
            'target' => $request->input('target'),
        ]);

        return response()->json([
            'success' => true,
            'created_count' => $notifications->count(),
            'notifications' => $notifications,
        ]);
    }
}
