<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\ProductCategoryResource;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::all();
        return ApiResponse::sendResponse(200,'show categories successfully',ProductCategoryResource::collection($categories));
    }

    public function show($id)
    {
        $category=DB::table('product_categories')->where('CategoryID',$id)->first();
        if($category){
            return ApiResponse::sendResponse(200,'show category successfully',new ProductCategoryResource($category));
        }else
        return ApiResponse::sendResponse(404,'not found',null);
    }

    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'Name' => 'required|string|max:255',
            'Description' => 'nullable|string',
            'CategoryFile' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);
        if($validator->fails()){
            return ApiResponse::sendResponse(401,$validator->errors(),null);
        }

        $filePath = null;
        if ($request->hasFile('CategoryFile')) {
            $file = $request->file('CategoryFile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/product_categories', $filename, 'public');
            
            if (!$filePath) {
            return ApiResponse::sendResponse(422, 'يجب رفع ملف للفئة', null);
            }
        }

        $categoryData = $request->all();
        $categoryData['CategoryFile'] = $filePath;

        $category = ProductCategory::create($categoryData);
        if($category){
            return ApiResponse::sendResponse(200,'Category insert successfully',new ProductCategoryResource($category));
        }else{
            return ApiResponse::sendResponse(404,'insert faild',null);
        }
    }

    public function update(Request $request, $id)
    {
        $validator=Validator::make($request->all(),[
            'Name' => 'required|string|max:255',
            'Description' => 'nullable|string',
        ]);
        if($validator->fails()){
            return ApiResponse::sendResponse(401,$validator->errors(),null);
        }
        $category = ProductCategory::find($id);
        if(!$category){
            return ApiResponse::sendResponse(404,'category not found',null);
        }

        if($category){
            $category->update($request->all());
            return ApiResponse::sendResponse(200,'category update successfully',new ProductCategoryResource($category));
        }else{
            return ApiResponse::sendResponse(404,'category update faild',null);
        }
    }

    public function destroy($id)
    {
        $category = ProductCategory::find($id);
        if(!$category){
        return ApiResponse::sendResponse(404,'not found',null);
        }
        $category->delete(); 
        return ApiResponse::sendResponse(200,'category delete successfully');
    }
}
