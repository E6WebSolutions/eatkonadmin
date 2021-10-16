@extends("restaurants.layouts.restaurantslayout")

@section("restaurantcontant")
<?php
if (isset($orderDetails)) {
    $order_id = $orderDetails['id'];
    $table_no = $orderDetails['table_no'];
    $customer_name = $orderDetails['customer_name'];
    $customer_phone = $orderDetails['customer_phone'];
    $order_type = $orderDetails['order_type'];
    $comments = $orderDetails['comments'];
    $tax = $orderDetails['tax'];
    $store_charge = $orderDetails['store_charge'];
    $total = $orderDetails['total'];
    $sub_total = $orderDetails['sub_total'];
    $mode = 'Edit';
} else {
    $order_id = '';
    $table_no = '';
    $customer_name = '';
    $customer_phone = '';
    $order_type = '';
    $comments = '';
    $tax = 0;
    $store_charge = 0;
    $total = '';
    $sub_total = '';
    $mode = 'Add';
}

?>
<style>
    .custom-alert {
        color: red;
    }
</style>

<div class="container-fluid">
    <div class="card mb-4">
        <!-- Card header -->
        <div class="card-header">
            <h3 class="mb-0">{{ $mode }} Walkin Order </h3>
            @if(session()->has("MSG"))
            <div class="alert alert-{{session()->get('TYPE')}}">
                <strong> <a>{{session()->get("MSG")}}</a></strong>
            </div>
            @endif
            <!-- @if($errors->any()) @include('admin.admin_layout.form_error') @endif -->
        </div>
        <!-- Card body -->
        <div class="card-body">
            <form method="post" action="{{route('store_admin.savewalkinOrder')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" name="payment_status" value="1">
                <input type="hidden" name="payment_type" value="CASH">
                <input type="hidden" name="addon" value="">
                <input type="hidden" name="discount" value="0">
                <input type="hidden" name="order_id" value="{{ $order_id }}">
                <!-- Form groups used in grid -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Table no</label>
                            <select class="form-control @error('table_no') is-invalid @enderror" name="table_no" id="table_no">
                                @foreach($tables as $tables)
                                <option value="{{ $tables->id }}" <?php if ($table_no != '' && $table_no == $tables->id) {
                                                                        echo 'selected';
                                                                    } ?>>{{ $tables->table_name }}</option>
                                @endforeach
                            </select>
                            @error('table_no')
                            <div class="custom-alert">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Customer Name</label>
                            <input type="text" name="customer_name" value="{{ $customer_name }}" class="form-control @error('customer_name') is-invalid @enderror" required>
                            @error('customer_name')
                            <div class="custom-alert">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Phone number</label>
                            <input type="text" name="customer_phone" value="{{ $customer_phone }}" class="form-control @error('customer_phone') is-invalid @enderror" required>
                            @error('customer_phone')
                            <div class="custom-alert">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Order Type</label>
                            <select class="form-control" name="order_type" required>
                                <option value="1" <?php if ($order_type != '' && $order_type == '1') {
                                                        echo 'selected';
                                                    } ?>>Dining</option>
                                <option value="2" <?php if ($order_type != '' && $order_type == '2') {
                                                        echo 'selected';
                                                    } ?>>Takeaway</option>
                                <option value="3" <?php if ($order_type != '' && $order_type == '3') {
                                                        echo 'selected';
                                                    } ?>>Delivery</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Comment</label>
                            <textarea class="form-control" name="comments" rows="3"> {{ $comments }} </textarea>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <label><strong>Order Details: </strong></label>
                    </div>
                </div>
                @if(isset($orderDetails) && count($orderDetails['order_details']) > 0)
                <div id="sub_operation_div0">
                    @foreach($orderDetails['order_details'] as $key => $_orderDetails)
                    @if($key == 0)
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="exampleFormControlSelect1">Store product list </label>
                                <select class="form-control select-drop" name="store_product[]" id="store_product" onchange="getProductPrice(this,'00')" required>
                                    <option value="">--select--</option>
                                    @foreach($products as $_products)
                                    <option value="{{ $_products->id }}" <?php if($_orderDetails['product_id'] == $_products->id){ echo 'selected'; } ?>>{{ $_products->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Product Price</label>
                                <input type="number" class="form-control" id="product_original_price00" name="product_original_price[]" value="{{ (int)$_orderDetails['price'] }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" class="form-control" id="product_qty00" name="product_qty[]" value="{{ (int)$_orderDetails['quantity'] }}" onkeyup="changeqty(this,'00')" >
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Price</label>
                                <input type="number" class="form-control ItemPrice" id="product_price00" name="product_price[]" value="{{ (int)$_orderDetails['price'] * (int)$_orderDetails['quantity'] }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label>&nbsp;</label><br>
                            <span class="btn btn-primary btn-sm" onclick="AddOperationSubDiv(0);"><span class="fa fa-plus"></span></span>
                        </div>
                    </div>
                    @else
                    <div class="row ProductConfigSubRow" id="ProductConfigSubRow{{ $key}}">
                        <div class="col-md-4">
                            <div class="form-group">
                                <select class="form-control select-drop" name="store_product[]" onchange="getProductPrice(this, <?php echo $key; ?>)" required>
                                    <option value="">--select--</option>
                                    <?php foreach ($products as $_products) { ?>
                                        <option value='<?= $_products->id ?>' <?php if($_orderDetails['product_id'] == $_products->id){ echo 'selected'; } ?> ><?= $_products->name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="number" class="form-control" name="product_original_price[]" id="product_original_price{{ $key }}" value="{{ (int)$_orderDetails['price'] }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="number" class="form-control" name="product_qty[]" id="product_qty{{ $key }}" value="{{ (int)$_orderDetails['quantity'] }}" onkeyup="changeqty(this, <?php echo $key; ?>)" >
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" class="form-control ItemPrice" name="product_price[]" id="product_price{{ $key }}" value="{{ (int)$_orderDetails['price'] * (int)$_orderDetails['quantity'] }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <span class="btn btn-danger btn-sm" onclick="RemoveOperationSubDiv(<?php echo $key; ?>);"><span class="fa fa-minus"></span></span>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
                @else
                <div id="sub_operation_div0">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="exampleFormControlSelect1">Store product list</label>
                                <select class="form-control select-drop" name="store_product[]" id="store_product" onchange="getProductPrice(this,'00')" required>
                                    <option value="">--select--</option>
                                    @foreach($products as $_products)
                                    <option value="{{ $_products->id }}">{{ $_products->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Product Price</label>
                                <input type="number" class="form-control" id="product_original_price00" name="product_original_price[]" value="" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" class="form-control" id="product_qty00" name="product_qty[]" value="" onkeyup="changeqty(this,'00')" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Price</label>
                                <input type="number" class="form-control ItemPrice" id="product_price00" name="product_price[]" value="" readonly>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label>&nbsp;</label><br>
                            <span class="btn btn-primary btn-sm" onclick="AddOperationSubDiv(0);"><span class="fa fa-plus"></span></span>
                        </div>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" style="display: none;">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group" style="display: none;">
                        </div>
                    </div>
                    <div class="col-md-2" style="text-align: right;">
                        <div class="form-group">
                            <label>Subtotal:</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="number" class="form-control" id="final_price" name="sub_total" value="{{ $sub_total }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" style="display: none;">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group" style="display: none;">
                        </div>
                    </div>
                    <div class="col-md-2" style="text-align: right;">
                        <div class="form-group">
                            <label>Service Charge:</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="number" class="form-control" id="store_charge" name="store_charge" value="{{ $store_charge }}" onkeyup="addCharges()">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" style="display: none;">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group" style="display: none;">
                        </div>
                    </div>
                    <div class="col-md-2" style="text-align: right;">
                        <div class="form-group">
                            <label>Tax(%):</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="number" class="form-control" id="tax" name="tax" step=".01" value="{{ $tax }}" onkeyup="addCharges()">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" style="display: none;">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group" style="display: none;">
                        </div>
                    </div>
                    <div class="col-md-2" style="text-align: right;">
                        <div class="form-group">
                            <label>Payable Total Price:</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="number" class="form-control" id="total" name="total" value="{{ $total }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        function AddOperationSubDiv(count) {
            var numItems = $('.ProductConfigSubRow').length
            if($('#ProductConfigSubRow'+ numItems).length){
                numItems += numItems;
            }
            console.log(numItems)
            var html = '';
            html += '<div class="row ProductConfigSubRow" id="ProductConfigSubRow' + numItems + '">';
            html += '<div class="col-md-4"> ';
            html += '<div class="form-group">';
            html += '<select class="form-control select-drop" name="store_product[]" onchange="getProductPrice(this,' + numItems + ')" required>';
            html += '<option value="">--select--</option>';
            <?php foreach ($products as $_products) { ?>
                html += "<option value='<?= $_products->id ?>'><?= $_products->name ?></option>";
            <?php } ?>
            html += '</select>';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-md-2"> ';
            html += '<div class="form-group">';
            html += '<input type="number" class="form-control" name="product_original_price[]" id="product_original_price' + numItems + '" value="" readonly>';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-md-2"> ';
            html += '<div class="form-group">';
            html += '<input type="number" class="form-control" name="product_qty[]" id="product_qty' + numItems + '" value="" onkeyup="changeqty(this,' + numItems + ')" readonly>';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-md-2"> ';
            html += '<div class="form-group">';
            html += '<input type="text" class="form-control ItemPrice" name="product_price[]" id="product_price' + numItems + '" value="" readonly>';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-md-1"> ';
            html += '<span class="btn btn-danger btn-sm" onclick="RemoveOperationSubDiv(' + numItems + ');"><span class="fa fa-minus"></span></span>';
            html += '</div>';
            html += '</div>';
            $('#sub_operation_div' + count).append(html);
            $('.select-drop').select2();
        }

        function RemoveOperationSubDiv(attribute_row_count) {
            $('#ProductConfigSubRow' + attribute_row_count).remove();
            getFinalAmount()
        }

        $(document).ready(function() {
            $('.select-drop').select2();
        });
    </script>
    @endsection