<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Validator;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return ApiResponse::sendResponse(200, 'All users retrieved', $users);
    }

    public function current(Request $request)
    {
        if ($request->user()) {
            return ApiResponse::sendResponse(200, 'Current user',$request->user());
        } else {
            return ApiResponse::sendResponse(401, 'no user in', null);
        }
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return ApiResponse::sendResponse(404, 'User not found', null);
        }
        return ApiResponse::sendResponse(200, 'User retrieved', $user);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'userType' => 'required|in:user,admin,product_manager',
            'phone' => 'nullable|string',
            'preferredLanguage' => 'nullable|string|max:10',
            'preferredTheme' => 'nullable|string|max:20',
        ]);


        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        return ApiResponse::sendResponse(201, 'User created successfully', $user);
    }

    public function update(Request $request, $id)
    {
       $user = User::find($id);
    if (!$user) {
        return ApiResponse::sendResponse(404, 'User not found', null);
    }

    $validator = Validator::make($request->all(), [
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:users,email,' . $id . ',UserID',
        'password' => 'sometimes|string|min:6',
        'userType' => 'sometimes|in:user,admin,product_manager',
        'phone' => 'nullable|string',
        'preferredLanguage' => 'nullable|string|max:10',
        'preferredTheme' => 'nullable|string|max:20',
    ]);

    if ($validator->fails()) {
        return ApiResponse::sendResponse(422, 'Validation error', $validator->errors());
    }

    $validated = $validator->validated();

    if (isset($validated['password'])) {
        $validated['password'] = Hash::make($validated['password']);
    }

    $user->update($validated);

    return ApiResponse::sendResponse(200, 'User updated successfully', $user);
}


    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return ApiResponse::sendResponse(404, 'User not found', null);
        }

        $user->delete();
        return ApiResponse::sendResponse(200, 'User deleted successfully');
    }
}
