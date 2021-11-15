var app = new Vue({

    el: '#app',
    data: {
        is_web: '1',
        type_id: '',
        total_guest: '1',
        storeData: {},
        storePaymentSettings: {},
        store_id: store_view_id,
        items: [
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
        ],
        // items: categoryArray,
        count: 0,
        added: true,
        res_search: '',

        error: '',
        loaded: false,
        isLoading: false,
        lat: '0.00',
        lon: '0.00',

        allCuisines: [],
        allServices: [],
        search_selector: [],
        selectedCuisineId: [],
        selectedServiceId: [],
        selectedSeatingOption: [],
        cuisine_id: '',
        service_id: '',
        seating_option: '',

        categories: [],
        RecommendedProduct: [
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' },
            { id: '0', name: '0' }
        ],
        AllStoreItem: [],

        productDetail: {},
        categoryItemsDetail: {},
        selected_menu_item: [],
        selectedItemId: [],
        res_search: '',
        errors: [],
        name: null,
        phone: null,
        order_type: null,
        comments: null,
        customer_phone: null,
        customer_orders: [],

        customer_name: null,
        customer_mobile: null,
        customer_table_number: null,
        customer_comment: null,
        customer_vehicle_no: null,
        discount: 0.00,
        coupon_code: '',
        is_COD: false,
        place_order_detail: {},
        is_vehicle: is_vehicle_flag,
        table_no: selected_table_no,

    },
    computed: {
        // filteredRecipes() {
        //     let tempRecipes = this.items
        //     console.log('monika call')
        //     // Process search input
        //     if (this.res_search != '' && this.res_search) {
        //         tempRecipes = tempRecipes.filter((item) => {
        //             return item.name
        //                 .toUpperCase()
        //                 .includes(this.res_search.toUpperCase())
        //         })
        //     }
        //     console.log(tempRecipes);
        //     return tempRecipes
        // }
    },


    mounted: function() {
        console.log('running------');
        // this.isLoading = false;

        if (localStorage.selectedItemId) {
            this.selectedItemId = JSON.parse(localStorage.selectedItemId);
        }
        if (localStorage.selectedItem) {
            this.selected_menu_item = JSON.parse(localStorage.selectedItem);
        }
        if (this.table_no > 0) {
            this.order_type = '1'
        }

        this.get();
        // this.getCuisines();
        // this.getServices();
    },

    methods: {
        acceptCustomerNumber() {
            var x = this.customer_mobile.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
            this.customer_mobile = !x[2] ? x[1] : x[1] + x[2] + (x[3] ? +x[3] : '');
        },
        acceptPhoneNumber() {
            var x = this.phone.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
            this.phone = !x[2] ? x[1] : x[1] + x[2] + (x[3] ? +x[3] : '');
        },
        get: function() {
            let vm = this;
            vm.isLoading = true;
            console.log('call the function-----end', this.res_search);
            axios.post(search_list_indexUrl, {
                    search: this.res_search,
                    view_id: this.store_id,
                })
                .then(function(response) {
                    // console.log('main rsponse-----------')

                    vm.items = []

                    vm.items = response.data.AllStoreCategory;
                    // console.log('after----',vm.items)
                    vm.RecommendedProduct = response.data.AllStoreRecommendedProduct;
                    // console.log(vm.RecommendedProduct.length)
                    vm.AllStoreItem = response.data.AllStoreItem;
                    vm.storeData = response.data.storeData;
                    vm.store_id = vm.storeData.view_id;
                    vm.storePaymentSettings = response.data.storePaymentSettings;
                    vm.isLoading = false;
                })
                .catch(function(error) {
                    vm.loaded = false;
                    vm.isLoading = false;
                });
        },
        getCategoryItems(CategoryItem) {
            this.categoryItemsDetail = CategoryItem
            $(".modal-body").animate({ scrollTop: 0 }, 400);
        },
        cartScroll() {
            $(".modal-body").animate({ scrollTop: 0 }, 400);
        },
        getItemDetail(itemData) {
            // console.log(itemData)
            this.productDetail = itemData
            $(".modal-body").animate({ scrollTop: 0 }, 400);
        },
        filtersearch: function() {
            this.get();
            // let vm = this;

            // let result = this.items
            // if (!this.res_search)
            //     return this.items = result

            // const res_search = this.res_search.toLowerCase()

            // const filter = event =>
            //     event.name.toLowerCase().includes(res_search)
            // // event.state.toLowerCase().includes(filterValue) ||
            // // event.tags.some(tag => tag.toLowerCase().includes(filterValue))

            // vm.items = result.filter(filter)
            // console.log(this.items)

        },
        clearfiltersearch: function() {
            this.res_search = ''
            this.get();
        },
        addToCart(selectedItem) {
            var temp = {
                item_id: selectedItem.id,
                name: selectedItem.name,
                price: selectedItem.price,
                quantity: '1',
                image: selectedItem.image_url,
            };
            this.selected_menu_item.push(temp);
            this.selectedItemId.push(selectedItem.id);
            localStorage.setItem('selectedItemId', JSON.stringify(this.selectedItemId))
            localStorage.setItem('selectedItem', JSON.stringify(this.selected_menu_item))
        },
        // this function for update food item quantity
        BindQuantity(item_id) {
            // console.log(localStorage.getItem("selectedItem"))
            if (item_id != undefined && localStorage.getItem("selectedItem") !== null && localStorage.getItem('selectedItem') != '') {
                var storageItemData = JSON.parse(localStorage.getItem('selectedItem'));
                var valObj = storageItemData.filter(function(elem) {
                    if (elem.item_id == item_id) return elem.quantity;
                });
                // console.log('valObj',valObj)
                return valObj[0].quantity;
            } else {
                return 1;
            }
        },
        // this function for incerase quantity of item
        increaseQuantity(item_id) {
            // console.log('item_id', item_id)
            var valObj = this.selected_menu_item.filter(function(elem) {
                if (elem.item_id == item_id) return elem.quantity;
            });
            // console.log(this.valObj)
            var updatedQua = 1 + parseFloat(valObj[0].quantity);
            valObj[0].quantity = updatedQua;
            localStorage.setItem('selectedItem', JSON.stringify(this.selected_menu_item))
            $('.' + valObj[0].name.replace(/[&\/\\#, +()$~%.:*?<>{}]/g, '_').toLowerCase()).val(updatedQua);
        },
        // this function for incerase quantity of item
        decreaseQuantity(item_id) {
            var valObj = this.selected_menu_item.filter(function(elem) {
                if (elem.item_id == item_id) return elem.quantity;
            });

            if (valObj[0].quantity > 1) {
                var updatedQua = parseFloat(valObj[0].quantity) - 1;
                valObj[0].quantity = updatedQua;
                localStorage.setItem('selectedItem', JSON.stringify(this.selected_menu_item))
                    // $('#'+item_id).val(updatedQua);
                $('.' + valObj[0].name.replace(/[&\/\\#, +()$~%.:*?<>{}]/g, '_').toLowerCase()).val(updatedQua);
            } else {
                var newMenuItemIdArray = this.selectedItemId.filter(function(e) {
                    return e != item_id;
                });

                this.selectedItemId = newMenuItemIdArray;
                localStorage.setItem('selectedItemId', JSON.stringify(this.selectedItemId));

                var newMenuItemArray = this.selected_menu_item.filter(function(elem) {
                    return elem.item_id != item_id;
                });
                this.selected_menu_item = newMenuItemArray;
                localStorage.setItem('selectedItem', JSON.stringify(this.selected_menu_item))
            }
        },
        // this function for bind order sub-total
        BindOrderSubTotal() {
            var subTotal = 0.00;
            $.each(this.selected_menu_item, function(key, value) {
                subTotal += parseFloat(parseFloat(value.price) * parseFloat(value.quantity))
            });
            this.subTotal = parseFloat(subTotal);
            return this.subTotal;
        },
        // this function for bind apply coupon code price
        applyCoupon() {
            let vm = this;
            vm.isLoading = true;
            if (this.coupon_code != '') {
                axios.post(apply_coupon_indexUrl, {
                        coupon_code: this.coupon_code,
                        view_id: this.store_id
                    })
                    .then(function(response) {
                        // console.log('main call waiter response-----------')
                        // console.log(response.data.payload)
                        $('#coupon_msg_div').css("display", "block")
                        if (response.data.success == true) {
                            $('#coupon_div').addClass("alert-success")
                            $('#coupon_div').removeClass("alert-danger")
                        } else {
                            $('#coupon_div').removeClass("alert-success")
                            $('#coupon_div').addClass("alert-danger")
                        }
                        $('#coupon_div').text(response.data.message)
                        if (response.data.payload != null) {
                            var tempDiscount = response.data.payload.discount
                            if (response.data.payload.discount_type != 'AMOUNT') {
                                var tempDiscount = parseFloat((parseFloat(vm.subTotal) * parseFloat(response.data.payload.discount)) / 100)
                            }
                            vm.discount = parseFloat(tempDiscount)
                        }
                    })
                    .catch(function(error) {
                        vm.loaded = false;
                        vm.isLoading = false;
                    });


            }
        },
        // this function for delete item from cart
        removeItem(item_id) {
            var newMenuItemIdArray = this.selectedItemId.filter(function(e) {
                return e != item_id;
            });

            this.selectedItemId = newMenuItemIdArray;
            localStorage.setItem('selectedItemId', JSON.stringify(this.selectedItemId));

            var newMenuItemArray = this.selected_menu_item.filter(function(elem) {
                return elem.item_id != item_id;
            });
            this.selected_menu_item = newMenuItemArray;
            localStorage.setItem('selectedItem', JSON.stringify(this.selected_menu_item))
        },
        checkForm: function(e) {
            this.errors = [];
            if (!this.name) this.errors.push({ type: 'name', msg: "Please enter Your Name." });
            if (!this.phone) this.errors.push({ type: 'phone', msg: "Please enter Your mobile number." });
            if (!this.order_type) this.errors.push({ type: 'order_type', msg: "Please select order type." });
            if (this.is_vehicle == true && !this.customer_vehicle_no) this.errors.push({ type: 'customer_vehicle_no', msg: "Please enter your vehicle number." });
            if (this.phone && this.phone.length < 10) {
                this.errors.push({ type: 'phone', msg: "Please enter valid mobile number." })
            }
            if (this.errors.length == 0) {
                $('#payment-method').modal('show');
            }
        },
        clearFormValidation() {
            this.errors = [];
            this.name = null
            this.phone = null
            if (this.table_no > 0) {
                this.order_type = '1'
            }
            // this.order_type = null
            this.customer_comment = null
            this.customer_mobile = null
            this.customer_name = null
            this.customer_table_number = null
            this.coupon_code = null
            $('#coupon_msg_div').css("display", "none")
        },
        get_orders: function() {
            let vm = this;
            vm.isLoading = true;
            // console.log('call get_order function-----end', this.customer_phone);
            // console.log(get_order_indexUrl)
            axios.post(get_order_indexUrl, {
                    customer_phone: this.customer_phone
                })
                .then(function(response) {
                    // console.log('main get order response-----------')
                    // console.log(response.data.payload.data)
                    vm.customer_orders = response.data.payload.data;
                })
                .catch(function(error) {
                    vm.loaded = false;
                    vm.isLoading = false;
                });
        },
        clearOrderData() {
            this.customer_orders = [];
            this.customer_phone = null
            this.isLoading = false

        },
        order_call_to_waiter(order_id) {
            $('#' + order_id).css("display", "block")
            let vm = this;
            vm.isLoading = true;
            // console.log('call waiter function-----end', order_id);
            // console.log(order_call_waiter_indexUrl)
            axios.post(order_call_waiter_indexUrl, {
                    order_id: order_id
                })
                .then(function(response) {
                    // console.log('main call waiter response-----------')
                    // console.log(response.data)
                    $('#' + order_id).css("display", "none")
                })
                .catch(function(error) {
                    vm.loaded = false;
                    vm.isLoading = false;
                });
        },
        checkWaiterCallForm: function(e) {

            // if (this.customer_name && this.customer_mobile && this.customer_table_number) return true;
            this.errors = [];
            if (!this.customer_name) this.errors.push({ type: 'customer_name', msg: "Please enter Your Name." });
            if (!this.customer_mobile) this.errors.push({ type: 'customer_mobile', msg: "Please enter Your mobile number." });
            // if (!this.customer_table_number) this.errors.push({ type: 'customer_table_number', msg: "Please select order type." });
            // e.preventDefault();
            if (this.customer_mobile.length < 10) {
                this.errors.push({ type: 'customer_mobile', msg: "Please enter valid mobile number." })
            }
            if (this.errors.length == 0) {
                let vm = this;
                vm.isLoading = true;
                axios.post(order_call_waiter_indexUrl, {
                        customer_name: this.customer_name,
                        customer_phone: this.customer_mobile,
                        store_id: this.store_id,
                        table_name: this.table_no,
                        comment: this.customer_comment
                    })
                    .then(function(response) {
                        // console.log('main call waiter response-----------')
                        // console.log(response.data)
                        $('#call-to-waiter').modal('hide');
                    })
                    .catch(function(error) {
                        vm.loaded = false;
                        vm.isLoading = false;
                    });
            }
        },
        check: function(e) {
            console.log(this.is_COD)
        },
        placeOrder() {
            let vm = this;
            vm.isLoading = true;
            var paymentMethod = (this.is_COD == true) ? 'CASH' : ''
            var cart = []
            $.each(this.selected_menu_item, function(key, value) {
                var tempCartObj = {
                    addon: null,
                    count: value.quantity,
                    extra: null,
                    itemId: value.item_id,
                    storeId: this.store_id
                }
                cart.push(tempCartObj)
            });
            var totalAmount = parseFloat(this.subTotal) + parseFloat(this.storeData.service_charge) + parseFloat(this.storeData.tax) - parseFloat(this.discount)
            axios.post(place_order_indexUrl, {
                    cart: cart,
                    comments: this.comments,
                    customer_name: this.name,
                    customer_phone: this.phone,
                    dob_customer: null,
                    order_type: this.order_type,
                    payment_status: 1,
                    payment_type: paymentMethod,
                    room_number: null,
                    store_charge: this.storeData.service_charge,
                    store_id: this.store_id,
                    sub_total: this.subTotal,
                    table_no: this.table_no,
                    tax: this.storeData.tax,
                    total: totalAmount,
                    coupon_name: this.coupon_code,
                    discount: this.discount,
                    vehicle_no: this.customer_vehicle_no
                })
                .then(function(response) {
                    if (response.data.success == true) {
                        $('#place-order').modal('show');
                        vm.place_order_detail = response.data.payload.new_order;
                        vm.customer_phone = response.data.payload.data[0].order_unique_id
                        localStorage.removeItem('selectedItemId');
                        localStorage.removeItem('selectedItem');
                        vm.selectedItemId = []
                        vm.selected_menu_item = []
                    }

                })
                .catch(function(error) {
                    vm.loaded = false;
                    vm.isLoading = false;
                });
        },
        check_order_status: function() {
            $('#cart').modal('hide');
            $('#payment-method').modal('hide');
            $('#my-order').modal('show');
            let vm = this;
            vm.isLoading = true;
            axios.post(get_order_indexUrl, {
                    customer_phone: this.customer_phone
                })
                .then(function(response) {
                    vm.customer_orders = response.data.payload.data;
                })
                .catch(function(error) {
                    vm.loaded = false;
                    vm.isLoading = false;
                });
        },
        get_menu() {
            $('#cart').modal('hide');
            $('#payment-method').modal('hide');
            $('#my-order').modal('hide');
            $('#call-to-waiter').modal('hide');
            $('#place-order').modal('hide');
            $('#item-popup').modal('hide');
            $('#item-popup-detail').modal('hide');
            $('#item-listing').modal('hide');
        }
    },
    directives: {
        carousel: {
            inserted: function(el) {
                // console.log('asdasdasdasd')
                $(el).owlCarousel({
                        loop: true,
                        margin: 10,
                        nav: false,
                        responsive: {
                            0: {
                                items: 2
                            },
                            600: {
                                items: 6
                            },
                            1000: {
                                items: 8
                            }
                        }
                    }).trigger('to.owl.carousel', app.items.length)
                    // console.log('slider')
            },
        },
        carousel2: {
            inserted: function(el) {
                $(el).owlCarousel({
                        loop: false,
                        margin: 10,
                        nav: false,
                        responsive: {
                            0: {
                                items: 2
                            },
                            600: {
                                items: 6
                            },
                            1000: {
                                items: 6
                            }
                        }
                    }).trigger('to.owl.carousel', app.RecommendedProduct.length)
                    // console.log("crousel inserted")
            },
        }
    }

});

// $('#supplier_id').select2({});