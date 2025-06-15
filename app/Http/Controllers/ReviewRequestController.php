<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ReviewRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\ReviewRequestStatusNotification;
use App\Helpers\ApiResponse;

class ReviewRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'UserID' => 'sometimes|exists:users,UserID',
            'ProductName' => 'required|string|max:255',
            'Description' => 'nullable|string',
            'ProductWeight' => 'required|numeric',
            'ProductPrice' => 'sometimes|numeric',
            'ProductFile' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $filePath = null;
        if ($request->hasFile('ProductFile')) {
            $file = $request->file('ProductFile');
            $filePath = $file->store('uploads/review_requests','public');
        }
        
        $review = ReviewRequest::create([
            'UserID' => $validated['UserID'] ?? null,
            'ProductName' => $validated['ProductName'],
            'ProductDescription' => $validated['Description'] ?? null,
            'ProductWeight' => $validated['ProductWeight'],
            'ProductPrice' => $validated['ProductPrice'] ?? null,
            'ProductImages' => $filePath,
            'SubmissionDate' => now(),
            'Status' => 'pending',
        ]);

        $admins = User::where('UserType', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new ReviewRequestStatusNotification('new_request', null, $review));
        }

        return ApiResponse::sendResponse(200, 'تم إرسال الطلب بنجاح', $review);
    }

    public function approve($id)
    {
        $review = ReviewRequest::find($id);
        if (!$review || $review->Status !== 'pending') {
            return ApiResponse::sendResponse(404, 'الطلب غير موجود أو مراجع مسبقاً');
        }


        $review->Status = 'approved';
        $review->AdminComments = 'تمت الموافقة';
        $review->save();

        $review->user->notify(new ReviewRequestStatusNotification('approved'));

        return ApiResponse::sendResponse(200, 'تمت الموافقة وإضافة المنتج', null);
    }

    public function reject(Request $request, $id)
    {
        $review = ReviewRequest::find($id);
        if (!$review || $review->Status !== 'pending') {
            return ApiResponse::sendResponse(404, 'الطلب غير موجود أو مراجع مسبقاً');
        }

        $comment = $request->input('AdminComments', 'تم الرفض من قبل الإدارة');
        $review->Status = 'rejected';
        $review->AdminComments = $comment;
        $review->save();

        $review->user->notify(new ReviewRequestStatusNotification('rejected', $comment));

        return ApiResponse::sendResponse(200, 'تم رفض الطلب بنجاح');
    }
}
