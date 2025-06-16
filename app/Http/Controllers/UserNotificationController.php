<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class UserNotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications;

        return ApiResponse::sendResponse(200, 'User notifications retrieved successfully', $notifications);
    }

   
}
