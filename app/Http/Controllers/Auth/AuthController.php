<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', Password::default()],
            'phone' => ['nullable', 'string'],
        ]);
    
        if($validator->fails()){
            return ApiResponse::sendResponse(422, 'Register validation errors', $validator->errors()->all());
        }
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'preferredLanguage' => $request->preferred_language ?? 'ar',
            'preferredTheme' => $request->preferred_theme ?? 'light',
        ]);
    
        $data['token'] = $user->createToken('jewelry_store')->plainTextToken;
        $data['name'] = $user->name;
        $data['email'] = $user->email;

    
        return ApiResponse::sendResponse(201, 'User account created successfully', $data);
    }

    public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if ($validator->fails()) {
        return ApiResponse::sendResponse(422, 'Validation errors', $validator->errors()->all());
    }

    // محاولة المصادقة مع التحقق من الحقول الصحيحة
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        $user = Auth::user();
        
        // حذف التوكنات القديمة للمستخدم
        // $user->tokens()->delete();

        // إنشاء توكن جديد
        $token = $user->createToken('jewelry_store')->plainTextToken;

        // إعداد البيانات المراد إرجاعها
        $data = [
            'user' => [
                'UserID' => $user->UserID,
                'name' => $user->name,
                'email' => $user->email,
                'userType' => $user->userType,
                'phone' => $user->phone,
                'preferredLanguage' => $user->preferredLanguage,
                'preferredTheme' => $user->preferredTheme
            ],
            'token' => $token
        ];

        return ApiResponse::sendResponse(200, 'Login successful', $data);
    }

    return ApiResponse::sendResponse(401, 'Invalid credentials', null);
}
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return ApiResponse::sendResponse(200, 'Logged out successfully', null);
    }
}