<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\ProductCategoryResource;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage; // تأكد من استيراد Storage

class ProductCategoryController extends Controller
{
    /**
     * عرض جميع فئات المنتجات.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // جلب جميع الفئات باستخدام Eloquent
        $categories = ProductCategory::all();
        return ApiResponse::sendResponse(200, 'عرض الفئات بنجاح', ProductCategoryResource::collection($categories));
    }

    /**
     * عرض فئة منتج معينة حسب المعرف.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // البحث عن الفئة باستخدام Eloquent
        $category = ProductCategory::find($id); // استخدام find() أسهل للبحث بالـ primary key

        if ($category) {
            return ApiResponse::sendResponse(200, 'عرض الفئة بنجاح', new ProductCategoryResource($category));
        } else {
            return ApiResponse::sendResponse(404, 'الفئة غير موجودة', null);
        }
    }

    /**
     * تخزين فئة منتج جديدة.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // قواعد التحقق من صحة البيانات المدخلة
        $validator = Validator::make($request->all(), [
            'Name' => 'required|string|max:255',
            'Description' => 'nullable|string',
            'CategoryFile' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // ملف الفئة مطلوب
        ]);

        // التحقق من فشل التحقق
        if ($validator->fails()) {
            return ApiResponse::sendResponse(422, $validator->errors(), null); // 422 Unprocessable Entity للأخطاء
        }

        $filePath = null;
        // التحقق مما إذا كان هناك ملف مرفوع باسم 'CategoryFile'
        if ($request->hasFile('CategoryFile')) {
            $file = $request->file('CategoryFile');
            // حفظ الملف في مجلد 'uploads/product_categories' على قرص التخزين 'public'
            // Laravel سيقوم تلقائيًا بإنشاء اسم ملف فريد (hash name)
            $filePath = $file->store('uploads/product_categories', 'public');
        }

        $categoryData = $request->all();
        // تعيين مسار الملف في بيانات الفئة ليتوافق مع اسم العمود في قاعدة البيانات
        $categoryData['CategoryFile'] = $filePath;

        // إنشاء سجل الفئة الجديد في قاعدة البيانات
        $category = ProductCategory::create($categoryData);

        if ($category) {
            return ApiResponse::sendResponse(201, 'تم إنشاء الفئة بنجاح', new ProductCategoryResource($category)); // 201 Created
        } else {
            return ApiResponse::sendResponse(500, 'فشل إنشاء الفئة', null); // 500 Internal Server Error في حالة فشل الإنشاء
        }
    }

    /**
     * تحديث فئة منتج موجودة.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // البحث عن الفئة المراد تحديثها
        $category = ProductCategory::find($id);

        if (!$category) {
            return ApiResponse::sendResponse(404, 'الفئة غير موجودة', null);
        }

        // قواعد التحقق من صحة البيانات المدخلة (باستخدام sometimes لأنها ليست مطلوبة دائمًا للتحديث)
        $validator = Validator::make($request->all(), [
            'Name' => 'sometimes|required|string|max:255',
            'Description' => 'nullable|string',
            'CategoryFile' => 'sometimes|file|mimes:jpg,jpeg,png,pdf|max:5120', // ملف الفئة اختياري للتحديث
        ]);

        // التحقق من فشل التحقق
        if ($validator->fails()) {
            return ApiResponse::sendResponse(422, $validator->errors(), null);
        }

        $categoryData = $request->all();
        
        // معالجة تحديث ملف الفئة
        if ($request->hasFile('CategoryFile')) {
            // حذف الملف القديم إذا كان موجودًا في التخزين العام
            if ($category->CategoryFile && Storage::disk('public')->exists($category->CategoryFile)) {
                Storage::disk('public')->delete($category->CategoryFile);
            }

            $file = $request->file('CategoryFile');
            // حفظ الملف الجديد
            $filePath = $file->store('uploads/product_categories', 'public');
            // تحديث مسار الملف الجديد في بيانات الفئة
            $categoryData['CategoryFile'] = $filePath; 
        } else {
            // إذا لم يتم إرسال ملف جديد، لا تقم بتعديل مسار الملف الحالي
            unset($categoryData['CategoryFile']); 
        }

        // تحديث سجل الفئة في قاعدة البيانات
        $category->update($categoryData);

        return ApiResponse::sendResponse(200, 'تم تحديث الفئة بنجاح', new ProductCategoryResource($category));
    }

    /**
     * حذف فئة منتج معينة.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // البحث عن الفئة المراد حذفها
        $category = ProductCategory::find($id);

        if (!$category) {
            return ApiResponse::sendResponse(404, 'الفئة غير موجودة', null);
        }

        // حذف الملف المرتبط بالفئة من التخزين إذا كان موجودًا
        if ($category->CategoryFile && Storage::disk('public')->exists($category->CategoryFile)) {
            Storage::disk('public')->delete($category->CategoryFile);
        }

        // حذف سجل الفئة من قاعدة البيانات
        $category->delete(); 
        return ApiResponse::sendResponse(200, 'تم حذف الفئة بنجاح');
    }
}
