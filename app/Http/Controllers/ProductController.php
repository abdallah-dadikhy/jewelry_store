<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Notifications\NewProductNotification;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return ApiResponse::sendResponse(200, 'Products retrieved successfully', ProductResource::collection($products));
    }

    public function show($id)
    {
        $product = Product::where('ProductID', $id)->first();
        if ($product) {
            return ApiResponse::sendResponse(200, 'Product retrieved successfully', new ProductResource($product));
        } else {
            return ApiResponse::sendResponse(404, 'Product not found', null);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Name' => 'required|string|max:100',
            'Description' => 'required|string',
            'Weight' => 'required|numeric',
            'Price' => 'required|numeric',
            'ProductFile' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'IsFeatured' => 'boolean',
            'CategoryID' => 'required|exists:product_categories,CategoryID',
        ]);

        if ($validator->fails()) {
            return ApiResponse::sendResponse(422, $validator->errors(), null);
        }

        // رفع الملف وحفظه
        $filePath = null;

        if ($request->hasFile('ProductFile')) {
            $file = $request->file('ProductFile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/products', $filename, 'public');
        }

        $productData = $request->all();
        $productData['ImageURL'] = $filePath;

        $product = Product::create($productData);

        // إرسال إشعار للأدمن
        $admins = User::where('UserType', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewProductNotification($product));
        }

        return ApiResponse::sendResponse(201, 'Product created successfully', new ProductResource($product));
    }

    public function update(Request $request, $id)
    {
        $product = Product::where('ProductID', $id)->first();

        if (!$product) {
            return ApiResponse::sendResponse(404, 'Product not found', null);
        }

        $validator = Validator::make($request->all(), [
            'Name' => 'sometimes|required|string|max:100',
            'Description' => 'sometimes|required|string',
            'Weight' => 'sometimes|required|numeric',
            'Price' => 'sometimes|required|numeric',
            'ProductFile' => 'sometimes|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'IsFeatured' => 'sometimes|boolean',
            'CategoryID' => 'sometimes|required|exists:product_categories,CategoryID',
            'SellerID' => 'sometimes|required|exists:users,UserID',
        ]);

        if ($validator->fails()) {
            return ApiResponse::sendResponse(422, $validator->errors(), null);
        }

        // إذا تم رفع ملف جديد
        if ($request->hasFile('ProductFile')) {
            $file = $request->file('ProductFile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/products', $filename, 'public');
            $request->merge(['ImageURL' => $filePath]);
        }

        $product->update($request->all());

        return ApiResponse::sendResponse(200, 'Product updated successfully', new ProductResource($product));
    }

    public function destroy($id)
    {
        $product = Product::where('ProductID', $id)->first();

        if (!$product) {
            return ApiResponse::sendResponse(404, 'Product not found', null);
        }

        $product->delete();
        return ApiResponse::sendResponse(200, 'Product deleted successfully');
    }

    public function featured()
    {
        $featuredProducts = Product::where('IsFeatured', true)->get();

        if ($featuredProducts->isEmpty()) {
            return ApiResponse::sendResponse(200, 'No featured products found', []);
        }

        return ApiResponse::sendResponse(200, 'Featured products retrieved successfully', ProductResource::collection($featuredProducts));
    }

    public function markAsFeatured(Request $request ,$id){
        $product = Product::where('ProductID', $id)->first();

        if (!$product) {
            return ApiResponse::sendResponse(404, 'Product not found', null);
        }
        $product->IsFeatured=true;
        $product->save();
        return ApiResponse::sendResponse(200,'product is featured',new ProductResource($product));

    }

    // فلترة المنتجات
    public function filter(Request $request)
    {
        $query = Product::query();

        if ($request->has('status')) {
            $query->where('Status', $request->status);
        }

        if ($request->has('min_weight')) {
            $query->where('Weight', '>=', $request->min_weight);
        }
        if ($request->has('max_weight')) {
            $query->where('Weight', '<=', $request->max_weight);
        }

        if ($request->has('min_price')) {
            $query->where('Price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('Price', '<=', $request->max_price);
        }

        if ($request->has('category_id')) {
            $query->where('CategoryID', $request->category_id);
        }

        if ($request->has('sort_by')) {
            $sortBy = $request->sort_by;
            $sortOrder = $request->get('sort_order', 'asc');

            if (in_array($sortBy, ['Price', 'created_at']) && in_array($sortOrder, ['asc', 'desc'])) {
                $query->orderBy($sortBy, $sortOrder);
            }
        }

        $products = $query->get();

        if ($products->isEmpty()) {
            return ApiResponse::sendResponse(200, 'No products found for given filters', []);
        }

        return ApiResponse::sendResponse(200, 'Filtered products retrieved successfully', ProductResource::collection($products));
    }

    public function aprovedproduct()
    {
        $products = Product::where('Status', 'approved')->get();
        return ApiResponse::sendResponse(200, 'Products retrieved successfully', ProductResource::collection($products));
    }
}
