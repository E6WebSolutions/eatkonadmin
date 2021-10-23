<?php

namespace App\Http\Controllers\StoreAdmin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Notification\NotificationController;
use App\Models\Addon;
use App\Models\AddonCategoryItem;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class ProductController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth:store');
    }


    public function addproducts(Request $request)
    {
        $data = request()->validate([
            'name' => 'required',
            'image_url' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'required',
            'category_id' => 'required',
            'is_veg' => '',
            'description' => '',
            'price' => 'required',
            'cooking_time' => 'required',
            'is_recommended' => '',

            'store_id' => ''
        ]);
        $data['store_id'] = auth()->id();
        if ($request->image_url != NULL) {
            $url = $request->file("image_url")->store('public/stores/product/images/');
            $data['image_url'] = str_replace("public", "storage", $url);
        }
        $insert = Product::create($data);
        if ($insert) {
            if ($request->addon_category_id != NULL) {
                $addon = new AddonCategoryItem();
                $addon->addon_category_id = $request->addon_category_id;
                $addon->product_id = $insert->id;
                $addon->store_id = auth()->id();
                $addon->save();
            }
            return Redirect::route("store_admin.products")->with(Toastr::success('Product Added successfully ', 'Success'));
        }
    }
    public function edit_products(Request $request, $id)
    {
        $data = request()->validate([
            'name' => 'required',
            'image_url' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'required',
            'category_id' => 'required',
            'is_veg' => '',
            'description' => '',
            'price' => 'required',
            'cooking_time' => 'required',
            'is_recommended' => '',
        ]);
        if ($request->image_url != NULL) {
            Storage::delete(str_replace("storage", "public", Product::find($id)->image_url));
            $url = $request->file("image_url")->store('public/stores/category/images/');
            $data['image_url'] = str_replace("public", "storage", $url);
        }

        $insert = Product::whereId($id)->update($data);
        if ($insert) {
            if ($request->addon_category_id != NULL) {
                AddonCategoryItem::where('product_id', '=', $id)->delete();
                $addon = new AddonCategoryItem();
                $addon->addon_category_id = $request->addon_category_id;
                $addon->product_id = $id;
                $addon->store_id = auth()->id();
                $addon->save();
            } else {
                AddonCategoryItem::where('product_id', '=', $id)->delete();
            }
        }
        return Redirect::route("store_admin.products")->with(Toastr::success('Product Updated successfully ', 'Success'));
    }
    public function delete_product(Request $request)
    {
        //if (Storage::delete(str_replace("storage", "public", Product::find($request->id)->image_url))) {
            Product::destroy($request->id);
            AddonCategoryItem::destroy($request->product_id);
        //}
        return back();
    }

    /**
     * added by monika
     * for save admin walkin order
     */
    public function savewalkinOrder(Request $request)
    {
        /* validate request params */
        $request->validate(
            [
                'customer_name' => 'required',
                'customer_phone' => 'required'
            ],
            [
                'customer_name.required' => 'Customer name is Required',
                'customer_phone.required' => 'Customer phone is Required'
            ]
        );

        // echo '<pre>';
        // print_r($_POST);
        // die;

        $data = $request->all();
        $edit_order_id = $data['order_id'];
        $order_unique_id = "ODR-" . time();
        $store_id = auth()->id();
        $orderData['store_id'] = $store_id;
        $orderData['order_unique_id'] = $order_unique_id;
        $orderData['customer_name'] = $data['customer_name'];
        $orderData['table_no'] = $data['table_no'];
        $orderData['customer_phone'] = $data['customer_phone'];
        $orderData['sub_total'] = $data['sub_total'];
        $orderData['discount'] = $data['discount'];
        $orderData['tax'] = $data['tax'];
        $orderData['store_charge'] = $data['store_charge'];
        $orderData['total'] = $data['total'];
        $orderData['comments'] = $data['comments'];
        $orderData['payment_status'] = $data['payment_status'];
        $orderData['order_type'] = $data['order_type'];
        $orderData['payment_type'] = $data['payment_type'];

        if ($edit_order_id != '' && $edit_order_id > 0) {
            $update = Order::whereId($edit_order_id)->update($orderData);
            $new_order = Order::whereId($edit_order_id)->first();
        } else {
            $new_order = Order::create($orderData);
        }
        $new_order['status'] = 1;
        $notification = new NotificationController();

        if ($new_order) {
            if ($edit_order_id != '' && $edit_order_id > 0) {
                OrderDetails::where('order_id', '=', $edit_order_id)->delete();
            }
            $order_id = Order::all()->where('order_unique_id', '=', $order_unique_id)->first()['id'];
            $items = array();
            if (count($data['store_product']) > 0 && count($data['product_original_price'])) {
                for ($i = 0; $i < count($data['store_product']); $i++) {
                    $temp = [];
                    $temp['order_id'] = $order_id;
                    $product = Product::all()->where('id', '=', $data['store_product'][$i])->first();
                    if ($data['addon'] == '') {
                        $temp['name'] = $product['name'];
                        $temp['price'] = $product['price'];
                    } else {
                        $addon = Addon::find($data['addon']);
                        $temp['name'] = $product['name'] . "-" . $addon->addon_name;
                        $temp['price'] = $addon->price;
                    }
                    $temp['quantity'] = $data['product_qty'][$i];
                    $orderDetail = OrderDetails::create($temp);
                }
            }

            // foreach ($orderItems as $value) {
            //     $temp = [];
            //     $temp['order_id'] = $order_id;
            //     $product = Product::all()->where('id', '=', $value['itemId'])->first();
            //     if ($value['addon'] == null) {
            //         $temp['name'] = $product['name'];
            //         $temp['price'] = $product['price'];
            //     } else {
            //         $addon = Addon::find($value['addon']);
            //         $temp['name'] = $product['name'] . "-" . $addon->addon_name;
            //         $temp['price'] = $addon->price;
            //     }
            //     $temp['quantity'] = $value['count'];
            //     $orderDetail = OrderDetails::create($temp);
            //     if ($value['extra'] != NULL) {
            //         $temp = array();
            //         foreach ($value['extra'] as $value) {
            //             $addon = Addon::find($value['addon_id']);
            //             $temp['order_detail_id'] = $orderDetail->id;
            //             $temp['addon_name'] = $addon->addon_name;
            //             $temp['addon_price'] = $addon->price;
            //             $temp['addon_count'] = $value['count'];
            //             OrderDetailAddon::create($temp);
            //         }
            //     }
            // }
            $response_data = Order::all()->where('customer_phone', '=', $request->customer_phone);


            $response = [];
            foreach ($response_data as $value)
                $response[] = $value;
            $new_order['render_whatsapp_message'] = $notification->WhatsAppOrderNotification(Order::with('orderDetails.OrderDetailsExtraAddon')->where('id', $new_order->id)->get()->toArray());
            try {
                $title = "New Order";
                $body = "New order is placed check here for more details";
                $notification->send_notification($title, $body, $store_id);
            } catch (\Exception $e) {
            }
            $new_order['render_whatsapp_message'] = str_replace("\n", "%0a", $new_order['render_whatsapp_message']);
            return Redirect::route("store_admin.orders")->with(Toastr::success('Walkin Order successfully ', 'Success'));
        }
    }
}
