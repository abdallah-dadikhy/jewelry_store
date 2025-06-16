<?php
namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Notifications\NewOrderNotification;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::with('orderDetails')->get(); 
        return ApiResponse::sendResponse(200, 'show orders successfuly',OrderResource::collection($orders));
    }


    public function show($id)
    {
        $order = Order::with('orderDetails')->find($id);
        if ($order) {
            return ApiResponse::sendResponse(200, 'show order successfuly',new OrderResource($order));
        } else {
            return ApiResponse::sendResponse(404, 'order not found', null);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'UserID' => 'required|exists:users,UserID',
            'OrderDate' => 'required|date',
            'Status' => 'required|string',
            'TotalAmount' => 'required|numeric',
            'ShippingAddress' => 'required|string',
            'PaymentMethod' => 'required|string',
            'Products' => 'required|array', 
            'Products.*.ProductID' => 'required|exists:products,ProductID',
            'Products.*.Quantity' => 'required|integer|min:1',
            'Products.*.PriceAtPurchase' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return ApiResponse::sendResponse(422, 'wrong', $validator->errors());
        }
          try {
        DB::beginTransaction();

        $order = new Order();
        $order->UserID = $request->UserID;
        $order->OrderDate = $request->OrderDate;
        $order->Status = $request->Status;
        $order->TotalAmount = $request->TotalAmount;
        $order->ShippingAddress = $request->ShippingAddress;
        $order->PaymentMethod = $request->PaymentMethod;
        $order->save(); 

        $orderId = $order->OrderID;

        foreach ($request->Products as $detail) {
            $orderDetail = new OrderDetails();
            $orderDetail->OrderID = $orderId;
            $orderDetail->ProductID = $detail['ProductID'];
            $orderDetail->Quantity = $detail['Quantity'];
            $orderDetail->PriceAtPurchase = $detail['PriceAtPurchase'];
            $orderDetail->save();
        }
        
        DB::commit(); 
        return ApiResponse::sendResponse(200, 'add order successfuly', $order);
        $admins = User::where('UserType', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new NewOrderNotification($order));
        }

        }catch (\Exception $e) {
            DB::rollBack(); 
            return ApiResponse::sendResponse(500, 'add order faild', $e->getMessage());
        }

    }

    public function update(Request $request, $id)
    {
         $order = Order::find($id);
        if (!$order) {
            return ApiResponse::sendResponse(404, 'order not found', null);
        }

        $validator = Validator::make($request->all(), [
            'Status' => 'sometimes|string',
            'ShippingAddress' => 'sometimes|string',
            'PaymentMethod' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return ApiResponse::sendResponse(422, 'wrong', $validator->errors());
        }

        $order->update($request->only(['Status', 'ShippingAddress', 'PaymentMethod']));
        return ApiResponse::sendResponse(200, 'update order successfuly', $order);

    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return ApiResponse::sendResponse(404, 'order not found', null);
        }

        $order->orderDetails()->delete(); 
        $order->delete();

        return ApiResponse::sendResponse(200, 'delete order successfuly');
    }
}
