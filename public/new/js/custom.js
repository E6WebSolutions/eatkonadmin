function getProductPrice(selectedItem, id) {
    
    $.ajax({
        url: base_path+'admin/store/product_detail',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: "POST",
        data: { product_id: selectedItem.value },
        success: function (data) {
            $('#product_original_price' + id + '').val(data)
            $('#product_price' + id + '').val(data)
            $('#product_qty' + id + '').val('1')
            $('#product_qty' + id + '').attr("readonly", false);
            getFinalAmount()
        }
    });

}

function changeqty(changeQty, id) {

    var changeQty = changeQty.value
    if (changeQty != 0) {
        var productPrice = $('#product_original_price' + id + '').val()
        var finalPrice = parseInt(productPrice) * parseInt(changeQty)
        $('#product_price' + id + '').val(finalPrice)
        getFinalAmount()
    }
}

function getFinalAmount() {
    var finalAmount = 0
    var productPrice = $(".ItemPrice");

    for (var i = 0; i < productPrice.length; i++) {
        if ($(productPrice[i]).val() != '') {
            finalAmount = parseInt(finalAmount) + parseInt($(productPrice[i]).val());
        }
    }
    $('#final_price').val(finalAmount)
    var store_charge = $('#store_charge').val()
    var taxPercentage = 5
    
    var taxAmount = 0
    if (finalAmount != '' && finalAmount > 0) {
        var taxAmount = parseInt((finalAmount * taxPercentage) / 100);
    }
    var tax = $('#tax').val(taxAmount)
    $('#total').val(parseInt(finalAmount) + parseInt(store_charge) + parseInt(taxAmount))
}

function addCharges() {
    getFinalAmount()
}

function orderPrint(orderId) {

    $.ajax({
        url: base_path+"admin/store/orders/details/" + orderId,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: "GET",
        success: function (data) {
            $('#orderDetailDiv').html(data)
            $('#thermalprintThis').printThis();
        }
    });

}
