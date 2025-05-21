<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\FavoriteResource;
use App\Http\Resources\ProductResource;
use App\Models\Favorites;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoritesController extends Controller
{
    // عرض كل المفضلات لمستخدم معيّن
    public function index(Request $request)
    {
        $userId = $request->user()->UserID;

        $favorites = Favorites::where('UserID', $userId)->with('product')->get();

        if ($favorites->isEmpty()) {
            return ApiResponse::sendResponse(200, 'No favorites found', []);
        }

        $products = $favorites->map(function ($favorite) {
            return new ProductResource($favorite->product);
        });

        return ApiResponse::sendResponse(200, 'Favorites retrieved successfully', $products);
    }

    // عرض مفضل محدد
    public function show($id)
    {
        $favorite = Favorites::with('product')->find($id);

        if (!$favorite) {
            return ApiResponse::sendResponse(404, 'Favorite not found', null);
        }

        return ApiResponse::sendResponse(200, 'Favorite retrieved successfully', new ProductResource($favorite->product));
    }

    // إضافة منتج للمفضلة
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ProductID' => 'required|exists:products,ProductID',
        ]);

        if ($validator->fails()) {
            return ApiResponse::sendResponse(401, $validator->errors(), null);
        }

        $userId = $request->user()->UserID;

        $exists = Favorites::where('UserID', $userId)
            ->where('ProductID', $request->ProductID)
            ->exists();

        if ($exists) {
            return ApiResponse::sendResponse(409, 'Product already in favorites', null);
        }

        $favorite = Favorites::create([
            'UserID' => $userId,
            'ProductID' => $request->ProductID,
            'AddedDate' => now(),
        ]);

        return ApiResponse::sendResponse(200, 'Product added to favorites',new FavoriteResource($favorite));
    }

    // حذف منتج من المفضلة
    public function destroy($id)
    {
        $favorite = Favorites::find($id);

        if (!$favorite) {
            return ApiResponse::sendResponse(404, 'Favorite not found', null);
        }

        $favorite->delete();

        return ApiResponse::sendResponse(200, 'Favorite deleted successfully');
    }
}
