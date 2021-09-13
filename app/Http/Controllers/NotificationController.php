<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $markAsRead = Notification::where('status', false)->update(['status' => true]);
        $notifications = Notification::latest()->get();
        return NotificationResource::collection($notifications);
    }
}
