<?php

namespace App\Http\Controllers\API;

use App\Application;
use App\Category;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Store;
use App\Models\Table;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 *
 */
class StoreController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
//        $this->middleware('auth:api');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function view(Request $request)
    {
        $data = Store::all()->where('id', "=", $request->shopId)->count();

        if ($data > 0) {
            $data = Store::all()->where('id', "=", $request->shopId);
            $response = array();
            foreach ($data as $key)
                $response = $key;

            $response['product_count'] = Product::all()->where('store_id', '=', $request->shopId)->count();
            return response()->json([
                "success" => true,
                "status" => "success",
                "payload" => [
                    'data' => $response,
                ]
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "status" => "error",
                "error" => ["code" => 400,
                    "type" => "data not found (ERROR:RT404)",
                    "message" => "We are unable to process your request at this time. Please try again later"
                ],
            ], 400);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $data = $request->all();
        $shopId = $data['shopId'];
        unset($data['shopId']);
        if ($request->password != NULL) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if (Store::whereId($shopId)->update($data)) {
            $response = Store::all()->where('id', "=", $shopId);
            foreach ($response as $key)
                $response = $key;
            $response['product_count'] = Product::all()->where('store_id', '=', $request->shopId)->count();
            return response()->json([
                "success" => true,
                "status" => "success",
                "payload" => [
                    'data' => $response,

                ]
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "status" => "error",
                "error" => [
                    "code" => 400,
                    "type" => "Bad Request (ERROR:RT404)",
                    "message" => "We are unable to process your request at this time. Please try again later"
                ],
            ], 400);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function orders_view(Request $request)
    {
        $data = $request->all();
        $shopId = $data['shopId'];
        $Order_data = Order::all()->SortByDesc('id')->where('store_id', '=', $request->shopId);
        $response = array();
        foreach ($Order_data as $data) {
            $response[] = $data;
        }
        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'data' => $response,

            ]
        ], 200);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function orders_view_details(Request $request)
    {
        $data = $request->all();
        $orderDetails = Order::with('orderDetails.OrderDetailsExtraAddon')->where('id', $data['order_id'])->get()->first();
        $response = $orderDetails;

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'data' => $response,

            ]
        ], 200);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_order_status(Request $request)
    {
        $data = $request->all();
        $order_id = $data['order_id'];
        unset($data['order_id']);

        Order::whereId($order_id)->update($data);

        $shopId = $data['store_id'];
        $Order_data = Order::all()->SortByDesc('id')->where('store_id', '=', $request->shopId);
        $response = array();
        foreach ($Order_data as $data) {
            $response[] = $data;
        }

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'data' => $response,
            ]
        ], 200);
    }

    public function copystore($newStoreId)
    {
//        dd("here");
        $originalStoreId = 3;
        $store = Store::with(['tables', 'categories.productinfos', 'products'])->where('id', $originalStoreId)->first();

        foreach ($store->tables as $table) {
            $newTable = new Table();
            $newTable->table_name = $table->table_name;
            $newTable->store_id = $newStoreId;
            $newTable->is_active = 1;
            $newTable->created_at = "2021-09-07 01:56:00";
            $newTable->updated_at = "2021-09-07 08:35:22";
            $newTable->table_code = null;
            $newTable->save();
        }
        foreach ($store->categories as $category) {
            $newCategory = new Category();
            $newCategory->name = $category->name;
            $newCategory->image_url = $category->image_url;
            $newCategory->store_id = $newStoreId;
            $newCategory->is_active = 1;
            $newCategory->created_at = "2021-09-07 01:56:00";
            $newCategory->updated_at = "2021-09-07 08:35:22";
            $newCategory->save();

            foreach ($category->productinfos as $product) {
                $newProduct = new Product();
                $newProduct->name = $product->name;
                $newProduct->category_id = $newCategory->id;
                $newProduct->store_id = $newStoreId;
                $newProduct->is_veg = $product->is_veg;
                $newProduct->is_active = 1;
                $newProduct->description = $product->description;
                $newProduct->price = $product->price;
                $newProduct->cooking_time = $product->cooking_time;
                $newProduct->image_url = $product->image_url;
                $newProduct->is_recommended = $product->is_recommended;
                $newProduct->created_at = "2021-09-07 01:56:00";
                $newProduct->updated_at = "2021-09-07 08:35:22";
                $newProduct->save();
            }

        }
        dd("here", $store);
    }
}
