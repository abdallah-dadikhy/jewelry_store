<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\ProductCategoryResource;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
class ProductCategoryController extends Controller
{

    public function index()
    {
        $categories = ProductCategory::all();
        return ApiResponse::sendResponse(200, 'عرض الفئات بنجاح', ProductCategoryResource::collection($categories));
    }


    public function show($id)
    {
        $category = ProductCategory::find($id); 

        if ($category) {
            return ApiResponse::sendResponse(200, 'عرض الفئة بنجاح', new ProductCategoryResource($category));
        } else {
            return ApiResponse::sendResponse(404, 'الفئة غير موجودة', null);
        }
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Name' => 'required|string|max:255',
            'Description' => 'nullable|string',
            'smithing'=>'required|numeric',
            'CategoryFile' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // ملف الفئة مطلوب
        ]);

        if ($validator->fails()) {
            return ApiResponse::sendResponse(422, $validator->errors(), null); // 422 Unprocessable Entity للأخطاء
        }

        $filePath = null;
        if ($request->hasFile('CategoryFile')) {
            $file = $request->file('CategoryFile');
            $filePath = $file->store('uploads/product_categories', 'public');
        }

        $categoryData = $request->all();
        $categoryData['CategoryFile'] = $filePath;

        $category = ProductCategory::create($categoryData);

        if ($category) {
            return ApiResponse::sendResponse(201, 'تم إنشاء الفئة بنجاح', new ProductCategoryResource($category)); // 201 Created
        } else {
            return ApiResponse::sendResponse(500, 'فشل إنشاء الفئة', null); // 500 Internal Server Error في حالة فشل الإنشاء
        }
    }

    public function update(Request $request, $id)
    {
        $category = ProductCategory::find($id);

        if (!$category) {
            return ApiResponse::sendResponse(404, 'الفئة غير موجودة', null);
        }

        $validator = Validator::make($request->all(), [
            'Name' => 'sometimes|required|string|max:255',
            'Description' => 'nullable|string',
            'smithing'=>'sometimes|numeric',
            'CategoryFile' => 'sometimes|file|mimes:jpg,jpeg,png,pdf|max:5120', 
        ]);

        if ($validator->fails()) {
            return ApiResponse::sendResponse(422, $validator->errors(), null);
        }

        $categoryData = $request->all();
        
        if ($request->hasFile('CategoryFile')) {
            if ($category->CategoryFile && Storage::disk('public')->exists($category->CategoryFile)) {
                Storage::disk('public')->delete($category->CategoryFile);
            }

            $file = $request->file('CategoryFile');
            $filePath = $file->store('uploads/product_categories', 'public');
            $categoryData['CategoryFile'] = $filePath; 
        } else {
            unset($categoryData['CategoryFile']); 
        }

        $category->update($categoryData);

        return ApiResponse::sendResponse(200, 'تم تحديث الفئة بنجاح', new ProductCategoryResource($category));
    }

    public function destroy($id)
    {
        $category = ProductCategory::find($id);

        if (!$category) {
            return ApiResponse::sendResponse(404, 'الفئة غير موجودة', null);
        }

        if ($category->CategoryFile && Storage::disk('public')->exists($category->CategoryFile)) {
            Storage::disk('public')->delete($category->CategoryFile);
        }

        $category->delete(); 
        return ApiResponse::sendResponse(200, 'تم حذف الفئة بنجاح');
    }
}
