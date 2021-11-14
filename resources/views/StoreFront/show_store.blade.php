@extends('StoreFront.home_layout.app')
@section('home_content')
<style>
    .food-top-slider .food-slide .owl-nav {
        display: none;
    }
</style>

<header class="custom-header" id="custom-header">
    <div class="container">
        <div class="location-header">
            <div class="r-detail">
                <p class="mb-0 font-semi text-black"><i class="icofont-google-map text-danger"></i> {{ $storeDetail->address }} <span class="line-header mx-2">|</span> <span class="text-white"> {{ $storeDetail->store_name }}</span> </p>
            </div>
        </div>
        <div class="logo-content text-center text-md-left text-lg-left">
            <div class="logo-inner-content d-flex justify-content-center">
                <a href="javascript:void(0)" class="d-inline-block">
                    <img src="{{asset($storeDetail->logo_url)}}" class="custom-logo mb-3 mt-3" alt="custom-logo">
                </a>
            </div>
        </div>
        <div class="contact-header">
            <div class="row">
                <div class="col-6">
                    <a href="tel:{{ $storeDetail->phone }}" class="font-semi text-black"><i class="text-danger icofont-ui-dial-phone marun-i"></i> {{ $storeDetail->phone }}</a>
                </div>
                @if($storeDetail->is_call_waiter_enable == 1)
                <div class="col-6">
                    <div class="right-content text-right" v-if="table_no > 0">
                        <a href="javascript:void(0)" class="font-semi text-black" data-toggle="modal" data-target="#call-to-waiter"><i class="icofont-boy text-dark marun-i"></i> Call the waiter</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</header>
<div class="search-container">
    <div class="container">
        <div class="search-result-header py-3">
            <div class="position-relative">
                <!-- <div class="form-group position-relative mb-0">
                    <input type="text" class="" v-model="res_search" id="res_search" name="res_search" placeholder="Search for Products.." v-on:keyup="filtersearch()">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </div> -->
                <div class="form-group input-group position-relative mb-0">
                    <input type="text" class="form-control" v-model="res_search" id="res_search" name="res_search" placeholder="Search for Products..">
                    {{-- <i class="fa fa-search" aria-hidden="true"></i> --}}
                    <div class="input-group-append">
                        <button class="btn btn-serach-bar" v-if="res_search != '' " @click="clearfiltersearch"><b class="fa fa-times" aria-hidden="true"></b></button>
                        <button class="btn btn-serach-bar" @click="filtersearch()">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="main_page" id="dashboard-page" v-if="isLoading == false">
    <!-- main content -->
    <div class="page-content">
        <div class="content-wrapper">
            <section class="food-top-slider pr-1">
                <div class="owl-carousel food-slide owl-theme" v-carousel>
                    <div class="item" v-for="item in items">
                        <a class="food-top-slide-content position-relative d-block" @click="getCategoryItems(item)" data-toggle="modal" data-target="#item-listing">
                            <img :src="item.image_url">
                            <span class="label-slide bg-black text-white font-semi">@{{ item.name }}</span>
                        </a>
                    </div>
                </div>
            </section>
            <section class="section-recommend">
                <div class="container">
                    <div class="section-header pb-3">
                        <h3 class="text-uppercase font-bold">Recommend for You</h3>
                    </div>
                </div>
                <div class="section-recommend-slide">
                    <div class="owl-carousel recommend-slide owl-theme" v-carousel2>
                        <div class="item" v-for="item in RecommendedProduct">
                            <a class="recommend-slide-content position-relative d-block" @click="getItemDetail(item)" data-toggle="modal" data-target="#item-popup">
                                <img :src="item.image_url">
                                <div class="normal-content pt-2">
                                    <div class="row">
                                        <div class="col-12 p-0">
                                            <h4 class="text-hidden font-small">@{{ item.name }}</h4>
                                        </div>
                                    </div>
                                    <price>Rs. @{{ item.price }}</price>

                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
            <section class="item-listing-block all-time-listing px-3">
                <div class="listing-category mb-3" v-for="category in AllStoreItem" v-if="category.AllItems.length > 0">
                    <div class="section-header pb-2">
                        <h3 class="text-uppercase font-bold mb-0">@{{ category.name.charAt(0).toUpperCase() + category.name.slice(1) }}</h3>
                    </div>
                    <div class="mb-3 filter-options collapse" :id="category.name.replace(/[&\/\\#, +()$~%.:*?<>{}]/g, '_').toLowerCase()">
                        <div class="item-list-content">
                            <div class="item-listing-inner-detail border-bottom py-2" v-for="menuItem in category.AllItems">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="s-item position-relative">
                                            <a class="item-img-block d-flex" data-toggle="modal" data-target="#item-popup" @click="getItemDetail(menuItem)">
                                                <img :src="menuItem.image_url">
                                                <div class="normal-content ml-3 w-100">
                                                    <p class="item-name mb-0 font-13 text-black">@{{ menuItem.name.charAt(0).toUpperCase() + menuItem.name.slice(1) }}</p>
                                                    <span class="item-badge-v2 d-block" v-if="menuItem.is_recommended == 1">rec</span>
                                                    <span class="item-badge-v2 d-block" style="background: none;" v-if="menuItem.is_recommended != 1"></span>
                                                    <div class="row align-items-center">
                                                        <div class="col-8">
                                                            <div class="item-price">
                                                                <price>Rs. @{{ menuItem.price}}</price>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="item-btn-customize text-right" v-if="selectedItemId.length && selectedItemId.includes(menuItem.id)">
                                                <div class="input-group mb-0 quantity-r aub-quantity d-flex align-items-center ml-3">
                                                    <div class="input-group-prepend">
                                                        <button class="btn btn-dark btn-sm qun-btn" id="minus-btn" @click="decreaseQuantity(menuItem.id)"><i class="fa fa-minus"></i></button>
                                                    </div>
                                                    <input type="number" class="form-control border-0 form-control-sm text-center p-0" :class="menuItem.name.replace(/[&\/\\#, +()$~%.:*?<>{}]/g, '_').toLowerCase()" min="1" :value="1" v-bind:value="BindQuantity(menuItem.id)" disabled>
                                                    <div class="input-group-prepend">
                                                        <button class="btn btn-dark btn-sm qun-btn" id="plus-btn" @click="increaseQuantity(menuItem.id)"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item-btn-customize text-right" v-else>
                                                <span class="btn btn-new-v2" @click="addToCart(menuItem)">+</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--cusine options end-->
                    <a href="javascript:void(0)" class="view-more theme-clr font-bold text-uppercase xs-font mb-30" data-toggle="collapse" :data-target="'#'+category.name.replace(/[&\/\\#, +()$~%.:*?<>{}]/g, '_').toLowerCase()">
                        <span class="view-all">View more</span>
                        <span class="hide-all">View less</span>
                    </a>
                </div>
            </section>
        </div>
    </div>

</div>
<!-- main content -->
<!-- footer section -->
<div class="custom-footer text-center text-md-left text-lg-left bg-black" v-if="isLoading == false">
    <div class="container">
        <div class="custom-footer-block">
            <div class="row">
                <div class="col-4">
                    <div class="footer-content text-center">
                        <a href="javascript:void(0)" class="d-block text-marun font-semi" @click="get_menu()">
                            <i class="d-block icofont-spoon-and-fork"></i>
                            Menu
                        </a>
                    </div>
                </div>
                <div class="col-4">
                    <div class="footer-content text-center">
                        <a href="javascript:void(0)" class="d-block text-black font-semi btn-cart position-relative" data-toggle="modal" data-target="#cart" @click="cartScroll()">
                            <i class="d-block icofont-cart"></i>
                            Cart
                            <span class="cart-count">
                                @{{ selectedItemId.length }}
                            </span>
                        </a>
                    </div>
                </div>
                <div class="col-4">
                    <div class="footer-content text-center">
                        <a href="javascript:void(0)" class="d-block text-black font-semi" data-toggle="modal" data-target="#my-order">
                            <i class="d-block icofont-paper"></i>
                            My Order
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- footer section -->

<!-- All popup -->
<!-- =========category menu listing menu========= -->
<div class="modal fade item-listing " id="item-listing">
    <div class="modal-dialog modal-dialog-centered m-0">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-footer p-0 py-3">
                <button class="back-btn p-0 px-3" data-dismiss="modal"> <i class="icofont-rounded-left back-page"></i>
                    Back </button>
            </div>
            <div class="modal-header mb-0 p-0 px-3">
                <h2 class="font-weight-bold text-white text-center mb-0 mb-3">@{{ categoryItemsDetail.name}}</h2>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="item-listing-block">
                    <div class="item-listing-header">
                        <h6 class="m-0 text-black"> Items </h6>
                    </div>
                    <div class="item-listing-inner-detail border-bottom py-2" v-for="item in categoryItemsDetail.AllItems">
                        <div class="row">
                            <div class="col-12">
                                <div class="s-item position-relative">
                                    <a class="item-img-block d-flex" data-toggle="modal" data-target="#item-popup" @click="getItemDetail(item)">
                                        <img :src="item.image_url">
                                        <div class="normal-content ml-3 w-100">
                                            <p class="item-name mb-0 font-13 text-black">@{{ item.name }}</p>
                                            <span class="item-badge-v2 d-block" v-if="item.is_recommended == 1">rec</span>
                                            <!-- <span class="item-badge-v1 d-block">Custom</span> -->
                                            <div class="row align-items-center">
                                                <div class="col-8">
                                                    <div class="item-price">
                                                        <price>Rs. @{{ item.price }}</price>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="item-btn-customize text-right" v-if="selectedItemId.length && selectedItemId.includes(item.id)">
                                        <div class="input-group mb-0 quantity-r aub-quantity d-flex align-items-center ml-3">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-dark btn-sm qun-btn" id="minus-btn" @click="decreaseQuantity(item.id)"><i class="fa fa-minus"></i></button>
                                            </div>
                                            <input type="number" class="form-control border-0 form-control-sm text-center p-0" :class="item.name.replace(/[&\/\\#, +()$~%.:*?<>{}]/g, '_').toLowerCase()" min="1" :value="1" v-bind:value="BindQuantity(item.id)" disabled>
                                            <div class="input-group-prepend">
                                                <button class="btn btn-dark btn-sm qun-btn" id="plus-btn" @click="increaseQuantity(item.id)"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item-btn-customize text-right" v-else>
                                        <span class="btn btn-new-v2" @click="addToCart(item)">+</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================== -->
<div class="modal fade customize modal-scroll modal-z" id="customized">
    <div class="modal-dialog modal-dialog-centered m-0">
        <div class="modal-content position-relative">
            <!-- Modal Header -->
            <div class="modal-heading-content px-3">
                <div class="row align-items-center">
                    <div class="col-6">
                        <h5 class="font-weight-bold text-center mb-0">Customization</h5>
                    </div>
                    <div class="col-6">
                        <div class="modal-footer p-0">
                            <button class="back-btn p-0" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal body -->
            <div class="modal-body px-3">
                <label class="form-label">3 In 1 Coco</label>
                <div class="cart-item p-3 mt-3">
                    <h5 class="mb-0">Small</h5>
                    <div class="flex-content d-flex align-items-center">
                        <price>Rs. 80.00</price>
                        <div class="input-group mb-0 quantity-r d-flex align-items-center ml-3">
                            <div class="input-group-prepend">
                                <button class="btn btn-dark btn-sm qun-btn" id="minus-btn"><i class="fa fa-minus"></i></button>
                            </div>
                            <input type="number" id="qty_input" class="form-control border-0 form-control-sm text-center p-0" value="0" min="1">
                            <div class="input-group-prepend">
                                <button class="btn btn-dark btn-sm qun-btn" id="plus-btn"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cart-item p-3 mt-3">
                    <h5 class="mb-0">Large</h5>
                    <div class="flex-content d-flex align-items-center">
                        <price>Rs. 80.00</price>
                        <div class="input-group mb-0 quantity-r d-flex align-items-center ml-3">
                            <div class="input-group-prepend">
                                <button class="btn btn-dark btn-sm qun-btn" id="minus-btn"><i class="fa fa-minus"></i></button>
                            </div>
                            <input type="number" id="qty_input" class="form-control border-0 form-control-sm text-center p-0" value="0" min="1">
                            <div class="input-group-prepend">
                                <button class="btn btn-dark btn-sm qun-btn" id="plus-btn"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-btn-row w-100">
                    <div class="row">
                        <div class="col-6 p-0">
                            <button class="btn-dark" data-dismiss="modal">Close</button>
                        </div>
                        <div class="col-6 p-0">
                            <button type="button" class="btn-marun">Save changes </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ========menu detail model========== -->

<div class="modal fade item-popup modal-scroll" id="item-popup">
    <div class="modal-dialog modal-dialog-centered m-0">
        <div class="modal-content position-relative">
            <!-- Modal Header -->
            <div class="modal-heading-content px-3">
                <button class="back-btn item-popup-back-btn p-0 px-0 font-small d-flex align-items-center" data-dismiss="modal"> <i class="icofont-rounded-left back-page"></i> Back</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body px-0 pt-0">
                <div class="item-img">
                    <img :src="productDetail.image_url" alt="Responsive image" width="100%" style="margin-bottom: 0px;">
                </div>
                <div class="item-detail p-3 pt-0">
                    <div class="item-popup-detail">
                        <h2 class="font-bold">@{{ productDetail.name }}</h2>
                    </div>
                    <div class="items-badge-list mb-3">
                        <span class="badge badge-danger mr-2">AVAILABLE</span>
                        <span class="badge badge-success mr-2" v-if="productDetail.is_recommended == 1"> RECOMMENDED</span>
                    </div>
                    <div class="mrp-detail">
                        <p class="font-semi">MRP : <b class="font-weight-bold">
                                <price>Rs. @{{ productDetail.price }}</price>
                            </b></p>
                    </div>
                    <div class="cook-time my-3">
                        <p class="font-weight-bold m-0"> Cooking Time</p>
                        <p class="text-muted m-0 font-semi">@{{ productDetail.cooking_time }} Minute </p>
                    </div>
                    <div class="product-detail my-3">
                        <p class="font-weight-bold mb-0"> Product Details</p>
                        <p class="text-muted small font-semi">@{{ productDetail.description }}</p>
                    </div>
                    <div class="product-detail my-3">
                        <p class="mb-0 font-weight-bold">** Photos show in menu may not look similar to actual dish and this photos are just for reference of dish</p>
                    </div>
                    <br>
                    <div class="item-listing-inner-detail mb-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="s-item position-relative">
                                    <div class="item-btn-customize text-right" v-if="selectedItemId.length && selectedItemId.includes(productDetail.id)">
                                        <div class="input-group mb-0 quantity-r aub-quantity d-flex align-items-center ml-3">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-dark btn-sm qun-btn" id="minus-btn" @click="decreaseQuantity(productDetail.id)"><i class="fa fa-minus"></i></button>
                                            </div>
                                            <input type="number" class="form-control border-0 form-control-sm text-center p-0" :class="productDetail.name.replace(/[&\/\\#, +()$~%.:*?<>{}]/g, '_').toLowerCase()" min="1" :value="1" v-bind:value="BindQuantity(productDetail.id)" disabled>
                                            <div class="input-group-prepend">
                                                <button class="btn btn-dark btn-sm qun-btn" id="plus-btn" @click="increaseQuantity(productDetail.id)"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item-btn-customize text-right" v-else>
                                        <span class="btn btn-new-v2" @click="addToCart(productDetail)">+</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item-listing-block mt-3">
                    <div class="item-listing-header header-bg px-3 py-2 mb-2">
                        <h6 class="m-0 text-black"> Maybe You Like this. </h6>
                    </div>
                    <div class="item-listing-inner-detail border-bottom py-2 px-3 mb-2" v-for="(menuItem,index) of RecommendedProduct" v-if="index < 2">
                        <div class="row">
                            <div class="col-12">
                                <div class="s-item position-relative">
                                    <a class="item-img-block d-flex" data-toggle="modal" data-target="#item-popup-detail" @click="getItemDetail(menuItem)">
                                        <img :src="menuItem.image_url">
                                        <div class="normal-content ml-3 w-100">
                                            <p class="item-name mb-0 font-13 text-black">@{{ menuItem.name.charAt(0).toUpperCase() + menuItem.name.slice(1) }}</p>
                                            <span class="item-badge-v2 d-block" v-if="menuItem.is_recommended == 1">rec</span>
                                            <div class="row align-items-center">
                                                <div class="col-8">
                                                    <div class="item-price">
                                                        <price>Rs. @{{ menuItem.price}}</price>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="item-btn-customize text-right" v-if="selectedItemId.length && selectedItemId.includes(menuItem.id)">
                                        <div class="input-group mb-0 quantity-r aub-quantity d-flex align-items-center ml-3">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-dark btn-sm qun-btn" id="minus-btn" @click="decreaseQuantity(menuItem.id)"><i class="fa fa-minus"></i></button>
                                            </div>
                                            <input type="number" class="form-control border-0 form-control-sm text-center p-0" :class="menuItem.name.replace(/[&\/\\#, +()$~%.:*?<>{}]/g, '_').toLowerCase()" min="1" :value="1" v-bind:value="BindQuantity(menuItem.id)" disabled>
                                            <div class="input-group-prepend">
                                                <button class="btn btn-dark btn-sm qun-btn" id="plus-btn" @click="increaseQuantity(menuItem.id)"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item-btn-customize text-right" v-else>
                                        <span class="btn btn-new-v2" @click="addToCart(menuItem)">+</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-btn-row w-100">
                    <div class="custom-footer-block">
                        <div class="row">
                            <div class="col-4">
                                <div class="footer-content text-center">
                                    <a href="javascript:void(0)" class="d-block text-marun font-semi" @click="get_menu()">
                                        <i class="d-block icofont-spoon-and-fork"></i>
                                        Menu
                                    </a>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="footer-content text-center">
                                    <a href="javascript:void(0)" class="d-block text-black font-semi btn-cart position-relative" data-toggle="modal" data-target="#cart">
                                        <i class="d-block icofont-cart"></i>
                                        Cart
                                        <span class="cart-count">
                                            @{{ selectedItemId.length }}
                                        </span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="footer-content text-center">
                                    <a href="javascript:void(0)" class="d-block text-black font-semi" data-toggle="modal" data-target="#my-order">
                                        <i class="d-block icofont-paper"></i>
                                        My Order
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade item-popup modal-scroll" id="item-popup-detail">
    <div class="modal-dialog modal-dialog-centered m-0">
        <div class="modal-content position-relative">
            <!-- Modal Header -->
            <div class="modal-heading-content px-3">
                <button class="back-btn item-popup-back-btn p-0 px-0 font-small d-flex align-items-center" data-dismiss="modal"> <i class="icofont-rounded-left back-page"></i> Back</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body px-0 pt-0">
                <div class="item-img">
                    <img :src="productDetail.image_url" alt="Responsive image" width="100%" style="margin-bottom: 0px;">
                </div>
                <div class="item-detail p-3 pt-0">
                    <div class="item-popup-detail">
                        <h2 class="font-bold">@{{ productDetail.name }}</h2>
                    </div>
                    <div class="items-badge-list mb-3">
                        <span class="badge badge-danger mr-2">AVAILABLE</span>
                        <span class="badge badge-success mr-2" v-if="productDetail.is_recommended == 1"> RECOMMENDED</span>
                    </div>
                    <div class="mrp-detail">
                        <p class="font-semi">MRP : <b class="font-weight-bold">
                                <price>Rs. @{{ productDetail.price }}</price>
                            </b></p>
                    </div>
                    <div class="cook-time my-3">
                        <p class="font-weight-bold m-0"> Cooking Time</p>
                        <p class="text-muted m-0 font-semi">@{{ productDetail.cooking_time }} Minute </p>
                    </div>
                    <div class="product-detail my-3">
                        <p class="font-weight-bold mb-0"> Product Details</p>
                        <p class="text-muted small font-semi">@{{ productDetail.description }}</p>
                    </div>
                    <div class="product-detail my-3">
                        <p class="mb-0 font-weight-bold">** Photos show in menu may not look similar to actual dish and this photos are just for reference of dish</p>
                    </div>
                    <br>
                    <div class="item-listing-inner-detail mb-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="s-item position-relative">
                                    <div class="item-btn-customize text-right" v-if="selectedItemId.length && selectedItemId.includes(productDetail.id)">
                                        <div class="input-group mb-0 quantity-r aub-quantity d-flex align-items-center ml-3">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-dark btn-sm qun-btn" id="minus-btn" @click="decreaseQuantity(productDetail.id)"><i class="fa fa-minus"></i></button>
                                            </div>
                                            <input type="number" class="form-control border-0 form-control-sm text-center p-0" :class="productDetail.name.replace(/[&\/\\#, +()$~%.:*?<>{}]/g, '_').toLowerCase()" min="1" :value="1" v-bind:value="BindQuantity(productDetail.id)" disabled>
                                            <div class="input-group-prepend">
                                                <button class="btn btn-dark btn-sm qun-btn" id="plus-btn" @click="increaseQuantity(productDetail.id)"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item-btn-customize text-right" v-else>
                                        <span class="btn btn-new-v2" @click="addToCart(productDetail)">+</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item-listing-block mt-3">
                    <div class="item-listing-header header-bg px-3 py-2 mb-2">
                        <h6 class="m-0 text-black"> Maybe You Like this. </h6>
                    </div>
                    <div class="item-listing-inner-detail border-bottom px-3 py-2 mb-2" v-for="(menuItem,index) of RecommendedProduct" v-if="index < 2">
                        <div class="row">
                            <div class="col-12">
                                <div class="s-item position-relative">
                                    <a class="item-img-block d-flex" data-toggle="modal" data-target="#item-popup" @click="getItemDetail(menuItem)">
                                        <img :src="menuItem.image_url">
                                        <div class="normal-content ml-3 w-100">
                                            <p class="item-name mb-0 font-13 text-black">@{{ menuItem.name.charAt(0).toUpperCase() + menuItem.name.slice(1) }}</p>
                                            <span class="item-badge-v2 d-block" v-if="menuItem.is_recommended == 1">rec</span>
                                            <div class="row align-items-center">
                                                <div class="col-8">
                                                    <div class="item-price">
                                                        <price>Rs. @{{ menuItem.price}}</price>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="item-btn-customize text-right" v-if="selectedItemId.length && selectedItemId.includes(menuItem.id)">
                                        <div class="input-group mb-0 quantity-r aub-quantity d-flex align-items-center ml-3">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-dark btn-sm qun-btn" id="minus-btn" @click="decreaseQuantity(menuItem.id)"><i class="fa fa-minus"></i></button>
                                            </div>
                                            <input type="number" class="form-control border-0 form-control-sm text-center p-0" :class="menuItem.name.replace(/[&\/\\#, +()$~%.:*?<>{}]/g, '_').toLowerCase()" min="1" :value="1" v-bind:value="BindQuantity(menuItem.id)" disabled>
                                            <div class="input-group-prepend">
                                                <button class="btn btn-dark btn-sm qun-btn" id="plus-btn" @click="increaseQuantity(menuItem.id)"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item-btn-customize text-right" v-else>
                                        <span class="btn btn-new-v2" @click="addToCart(menuItem)">+</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-btn-row w-100">
                    <div class="custom-footer-block">
                        <div class="row">
                            <div class="col-4">
                                <div class="footer-content text-center">
                                    <a href="javascript:void(0)" class="d-block text-marun font-semi" @click="get_menu()">
                                        <i class="d-block icofont-spoon-and-fork"></i>
                                        Menu
                                    </a>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="footer-content text-center">
                                    <a href="javascript:void(0)" class="d-block text-black font-semi btn-cart position-relative" data-toggle="modal" data-target="#cart">
                                        <i class="d-block icofont-cart"></i>
                                        Cart
                                        <span class="cart-count">
                                            @{{ selectedItemId.length }}
                                        </span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="footer-content text-center">
                                    <a href="javascript:void(0)" class="d-block text-black font-semi" data-toggle="modal" data-target="#my-order">
                                        <i class="d-block icofont-paper"></i>
                                        My Order
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- =========cart model========= -->
<div class="modal fade cart modal-z" id="cart">
    <div class="modal-dialog modal-dialog-centered m-0">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-heading-content px-3">
                <button class="back-btn item-popup-back-btn p-0 px-0 font-small d-flex align-items-center" data-dismiss="modal" @click="clearFormValidation()" v-if="selected_menu_item.length > 0"> <i class="icofont-rounded-left back-page"></i> Cart</button>
                <button class="back-btn item-popup-back-btn p-0 px-0 font-small d-flex align-items-center" data-dismiss="modal" @click="clearFormValidation()" v-if="selected_menu_item.length == 0"> <i class="icofont-rounded-left back-page"></i> Your cart is empty.</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body header-bg cart px-0" v-if="selected_menu_item.length > 0">
                <div class="cart-block mb-3">
                    <div class="cart-listing-block py-2 border-bottom" v-for="menuItem in selected_menu_item">
                        <div class="row align-items-center">
                            <div class="col-5">
                                <div class="flex-content">
                                    <p class="text-black"><i class="icofont-ui-delete text-danger mr-2" @click="removeItem(menuItem.item_id)"></i>@{{ menuItem.name }}</p>
                                </div>
                            </div>
                            <div class="col-7 p-0">
                                <div class="flex-content d-flex align-items-center justify-content-end pr-3">
                                    <div class="input-group mb-0 quantity-r d-flex align-items-center ml-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-dark btn-sm qun-btn" id="minus-btn" @click="decreaseQuantity(menuItem.item_id)"><i class="fa fa-minus"></i></button>
                                        </div>
                                        <input type="number" class="form-control border-0 form-control-sm text-center p-0" :class="menuItem.name.replace(/[&\/\\#, +()$~%.:*?<>{}]/g, '_').toLowerCase()" min="1" :value="1" v-bind:value="BindQuantity(menuItem.item_id)" disabled>
                                        <div class="input-group-prepend">
                                            <button class="btn btn-dark btn-sm qun-btn" id="plus-btn" @click="increaseQuantity(menuItem.item_id)"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <div class="text-black ml-2 item-price">Rs. @{{ menuItem.price}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <form id="app" @submit="checkForm" action="/something" method="post"> -->
                <div class="cart-block mb-3">
                    <div class="cart-form pt-3">
                        <div class="form-group mb-4">
                            <label for="exampleInputOLDPassword1"> Name *</label>
                            <input name="name" id="name" v-model="name" type="text" placeholder="Name" class="form-control" value="">
                            <p v-for="error in errors" v-if="errors.length && error.type == 'name'" class="validation-error">@{{ error.msg }}</p>
                        </div>
                        <div class="form-group mb-4">
                            <label for="exampleInputNEWPassword1">Phone Number *</label>
                            <input @input="acceptPhoneNumber" type="text" id="phone" v-model="phone" placeholder="Phone Number" class="form-control" name="phone" value="">
                            <p v-for="error in errors" v-if="errors.length && error.type == 'phone'" class="validation-error">@{{ error.msg }}</p>
                        </div>
                        <div class="form-group mb-4">
                            <label for="exampleInputNEWPassword1">Order Type *</label>
                            <select type="text" id="order_type" v-model="order_type" class="form-control" name="order_type" placeholder="Enter Your Table No">
                                <option value="">Order Type</option>
                                <option value="1" <?php if ($storeDetail->dining_enable != 1) {
                                                        echo 'disabled';
                                                    } ?>>Dining</option>
                                <option value="2" <?php if ($storeDetail->takeaway_enable != 1) {
                                                        echo 'disabled';
                                                    } ?>>Takeaway</option>
                                <option value="3" <?php if ($storeDetail->delivery_enable != 1) {
                                                        echo 'disabled';
                                                    } ?>>Delivery</option>
                                <option value="4" <?php if ($storeDetail->is_room_delivery_enable != 1) {
                                                        echo 'disabled';
                                                    } ?>>Room</option>
                            </select>
                            <p v-for="error in errors" v-if="errors.length && error.type == 'order_type'" class="validation-error">@{{ error.msg }}</p>
                        </div>
                        <div class="form-group mb-4">
                            <label for="exampleInputNEWPassword1">Table Number</label>
                            <input type="text" class="form-control" :value="table_no" disabled>
                        </div>
                        <div class="form-group mb-4" v-if="is_vehicle == true">
                            <label for="exampleInputNEWPassword1">Vehicle Number </label><input type="text" placeholder="Vehicle number" class="form-control" name="customer_vehicle_no" v-model="customer_vehicle_no" value="">
                            <p v-for="error in errors" v-if="errors.length && error.type == 'customer_vehicle_no'" class="validation-error">@{{ error.msg }}</p>
                        </div>
                        <div class="form-group mb-4">
                            <label for="exampleInputNEWPassword1">Comment </label><input type="text" placeholder="Comment" class="form-control" name="comments" v-model="comments" value="">
                        </div>

                    </div>
                </div>
                <div class="cart-block mb-3">
                    <div class="cart-form form-code pt-2 pb-3">
                        <!-- <form> -->
                        <div class="row align-items-center">
                            <div class="col-8 m-0 pr-1">
                                <input placeholder="Enter Promo Code" type="text" class="form-control" id="coupon_code" name="coupon_code" v-model="coupon_code">
                            </div>
                            <div class="col-4 pl-1">
                                <button type="button" class="btn btn-success btn-block" @click="applyCoupon()">Apply</button>
                            </div>
                        </div>
                        <br>
                        <div class="row align-items-center" style="display:none" id="coupon_msg_div">
                            <div class="col-12 m-0 pr-1">
                                <div class="alert padding-top-2" id="coupon_div"> </div>
                            </div>
                        </div>
                        <!-- </form> -->
                    </div>
                </div>
                <div class="cart-block mb-3">
                    <div class="sub-total-listing pb-4">
                        <div class="listing-block border-bottom py-3">
                            <div class="row">
                                <div class="col-6">
                                    <p>Subtotal</p>
                                </div>
                                <div class="col-6 text-right">
                                    <price class="text-black">Rs. <span v-bind:text="BindOrderSubTotal()">@{{ subTotal }}</span></price>
                                </div>
                            </div>
                        </div>
                        <div class="listing-block border-bottom py-3">
                            <div class="row">
                                <div class="col-6">
                                    <p>Apply Coupon</p>
                                </div>
                                <div class="col-6 text-right">
                                    <price>- Rs. @{{ discount }}</price>
                                </div>
                            </div>
                        </div>
                        <div class="listing-block border-bottom py-3">
                            <div class="row">
                                <div class="col-6">
                                    <p>Service Charge</p>
                                </div>
                                <div class="col-6 text-right">
                                    <price>Rs. @{{ parseFloat(storeData.service_charge) }}</price>
                                </div>
                            </div>
                        </div>
                        <div class="listing-block border-bottom py-3">
                            <div class="row">
                                <div class="col-6">
                                    <p>Tax %</p>
                                </div>
                                <div class="col-6 text-right">
                                    <price>Rs. @{{ parseFloat(storeData.tax) }}</price>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-btn-row w-100 bg-green px-4 py-2">
                    <div class="row align-items-center">
                        <div class="col-12 p-0 pay-now">
                            <a href="javascript:void(0)" @click="checkForm()">
                                <div class="more text-center text-md-left text-lg-left total-cost">
                                    <h6 class="m-0">Total Cost: <price>Rs. @{{ parseFloat(subTotal) + parseFloat(storeData . service_charge) + parseFloat(storeData . tax) - parseFloat(discount) }}</span></price>
                                    </h6>
                                    <p class="m-0">Confirm your order.</p>
                                </div>
                            </a>
                        </div>
                        <!-- <div class="col-4 p-0 text-right">
                                <button type="submit"><i class="icofont-simple-right"></i></button>
                            </div> -->
                        <!-- <div class="col-4 p-0 text-right cart-form-back">
                            <i class="icofont-simple-right" @click="checkForm()"></i>
                        </div> -->
                    </div>
                </div>
                <!-- </form> -->
            </div>
            <div class="modal-body header-bg cart px-3" v-if="selected_menu_item.length == 0">
                <div class="input-group mt-0 rounded shadow-sm overflow-hidden bg-white">
                    <img class="w-100" src="{{asset('assets_store_front/images/bag.png')}}">
                </div>
                <div class="modal-btn-row w-100 red-bg px-3 py-3 cart-emapty">
                    <div class="row align-items-center">
                        <div class="col-12 p-0">
                            <div class="more text-center white-bg">
                                <h6 class="m-0 text-white">Your cart is empty.</h6>
                            </div>
                        </div>
                        <div class="col-12 p-0 text-center text-black">
                            <button class="back-btn back-bottom item-popup-back-btn p-0 px-0 font-small d-flex align-items-center" data-dismiss="modal"><i class="icofont-rounded-left back-page"></i> Back to Menu</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========my order model========== -->

<div class="modal fade my-order" id="my-order">
    <div class="modal-dialog modal-dialog-centered m-0">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-heading-content px-3">
                <button class="back-btn item-popup-back-btn p-0 px-0 font-small d-flex align-items-center" data-dismiss="modal" @click="clearOrderData()"> <i class="icofont-rounded-left back-page"></i> My Orders</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body header-bg cart px-3">
                <!-- <form> -->
                <div class="input-group mt-0 rounded shadow-sm overflow-hidden bg-white">
                    <div class="input-group-prepend"><button class="border-0 btn btn-outline-secondary text-dark bg-white"><i class="icofont-search"></i></button></div>
                    <input type="text" v-model="customer_phone" class="shadow-none border-0 form-control pl-0" name="customer_phone" required="" placeholder="Phone Number/Order Id *" value="">
                </div>
                <div class="text-center" style="margin-top: 20px;"><button type="submit" class="btn red-bg text-white btn-block btn-lg" @click="get_orders()">Search Order</button></div>
                <!-- </form> -->

                <div class="mb-2 padding-order-details s-order mt-3" v-for="customer_order in customer_orders">
                    <div class="p-3-v2 px-3 pt-3 pb-3 bg-white s-order-block mb-3">
                        <div class="row">
                            <div class="col-6">
                                <a class="mr-5 btn btn-square min-width-125  mb-10 order-status-button text-white false btn-warning btn-r-warning" v-if="customer_order.status == 1">@{{ 'Order Pending' }} </a>
                                <a class="mr-5 btn btn-square min-width-125  mb-10 order-status-button text-white false btn-warning btn-r-accepted" v-if="customer_order.status == 2">@{{ 'Ready to Serve' }} </a>
                                <a class="mr-5 btn btn-square min-width-125  mb-10 order-status-button text-white false btn-warning btn-r-rejected" v-if="customer_order.status == 3">@{{ 'Rejected' }} </a>
                                <a class="mr-5 btn btn-square min-width-125  mb-10 order-status-button text-white false btn-warning btn-r-complete" v-if="(customer_order.status == 5) || (customer_order.status == 4)">@{{ 'Order Completed' }} </a>
                            </div>
                            <div class="col-6">
                                <div class="text-right" v-if="customer_order.call_waiter_enabled == 1 && table_no > 0">
                                    <a class=" btn btn-square btn-secondary min-width-125 mb-10 order-status-button text-white" @click="order_call_to_waiter(customer_order.id)">Call The Waiter
                                    </a>
                                    <span :id="customer_order.id" style="display: none;">calling......</span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="flex-auto-v2">
                            <h6 class="font-w700 mb-0 oderid-color-v2">@{{ customer_order.order_unique_id}}</h6><span class="text-muted pull-right-v2 my-2 d-block">4:22:50 am / <b class="text-danger">@{{ customer_order.created_at}}</b></span>
                            <h6 class="font-w600">Store : @{{ customer_order.store_name }}</h6>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <div class="content"><b>Bill Amount :</b></div>
                            </div>
                            <div class="col-6">
                                <div class="text-right"><b>
                                        <price><b>Rs. @{{ customer_order.total }}</b></price>
                                    </b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- =======call to waiter model=========== -->
<div class="modal fade cart modal-z" id="call-to-waiter">
    <div class="modal-dialog modal-dialog-centered m-0">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-heading-content px-3">
                <button class="back-btn item-popup-back-btn p-0 px-0 font-small d-flex align-items-center" data-dismiss="modal" @click="clearFormValidation()"> <i class="icofont-rounded-left back-page"> Call The Waiter </i> </button>
            </div>
            <!-- Modal body -->
            <div class="modal-body header-bg cart px-0">
                <!-- <form id="waiter_call" @submit="checkWaiterCallForm" action="/something" method="post"> -->
                <div class="cart-block mb-3">
                    <div class="cart-form pt-3">
                        <div class="form-group mb-4">
                            <label for="exampleInputOLDPassword1"> Name *</label>
                            <input name="customer_name" id="customer_name" v-model="customer_name" type="text" placeholder="Name" class="form-control" value="">
                            <p v-for="error in errors" v-if="errors.length && error.type == 'customer_name'" class="validation-error">@{{ error.msg }}</p>
                        </div>
                        <div class="form-group mb-4">
                            <label for="exampleInputNEWPassword1">Phone Number *</label>
                            <input @input="acceptCustomerNumber" type="text" id="customer_mobile" v-model="customer_mobile" placeholder="Phone Number" class="form-control" name="customer_mobile" value="">
                            <p v-for="error in errors" v-if="errors.length && error.type == 'customer_mobile'" class="validation-error">@{{ error.msg }}</p>
                        </div>
                        <div class="form-group mb-4">
                            <label for="exampleInputNEWPassword1">Comment </label>
                            <input type="text" placeholder="Comment" class="form-control" name="customer_comment" value="" v-model="customer_comment">
                        </div>
                        <div class="form-group mb-4">
                            <label for="exampleInputNEWPassword1">Your Table no</label>
                            <input type="text" class="form-control" :value="table_no" disabled>
                            <!-- <label for="table_no"><strong> @{{ table_no }} </strong></label> -->
                            <!-- <select type="text" id="customer_table_number" v-model="customer_table_number" class="form-control" name="customer_table_number">
                                <option value="">Select Your Table</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select> -->
                            <p v-for="error in errors" v-if="errors.length && error.type == 'customer_table_number'" class="validation-error">@{{ error.msg }}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-btn-row w-100 bg-green px-3 py-3 cart-emapty">
                    <div class="row align-items-center">
                        <div class="col-12 p-0 text-center text-black">
                            <button class="back-btn back-bottom item-popup-back-btn p-0 px-0 font-small d-flex align-items-center" @click="checkWaiterCallForm()">Call Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========payment model========== -->
<div class="modal fade cart modal-z payment-mathod" id="payment-method">
    <div class="modal-dialog modal-dialog-centered m-0">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-heading-content px-3">
                <button class="back-btn item-popup-back-btn p-0 px-0 font-small d-flex align-items-center" data-dismiss="modal" @click="clearFormValidation()"> <i class="icofont-rounded-left back-page"></i>
                    <h6 class="mb-0">Payment Method</h6>
                </button>
            </div>
            <!-- Modal body -->
            <div class="modal-body header-bg cart px-0 payment-accordian" id="accordion">
                <!-- <form id="waiter_call" @submit="checkWaiterCallForm" action="/something" method="post"> -->
                <div class="cart-block mb-3" v-if="storePaymentSettings.IsCodEnabled == 1">
                    <div class="cart-form p-0">
                        <div class="card p-0">
                            <div class="card-header p-0">
                                <a class="card-link p-3 d-block" data-toggle="collapse" href="#cod">
                                    <i class="icofont-cash-on-delivery mr-3"></i> Cash on Delivery
                                </a>
                            </div>
                            <div id="cod" class="collapse" data-parent="#accordion">
                                <div class="card-body p-3">
                                    <input type="checkbox" value="" id="is_COD" v-model="is_COD" @change="check($event)">
                                    <label for="vehicle1"> Cash</label>
                                    <p>Please keep exact change handy to help us serve you better </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cart-block mb-3" v-if="storePaymentSettings.IsStripeEnabled == 1">
                    <div class="cart-form p-0">
                        <div class="card p-0">
                            <div class="card-header p-0">
                                <a class="collapsed card-link p-3 d-block" data-toggle="collapse" href="#stripe">
                                    <i class="icofont-stripe-alt mr-3"></i> Stripe
                                </a>
                            </div>
                            <div id="stripe" class="collapse" data-parent="#accordion">
                                <div class="card-body p-3">
                                    <div class="panel-body">
                                        @if (Session::has('success'))
                                        <div class="alert alert-success text-center">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                            <p>{{ Session::get('success') }}</p>
                                        </div>
                                        @endif
                                        <form role="form" action="{{ route('stripe.post') }}" method="post" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="pk_test_51JZVw5SBo1OIRmJ2oarURiSJwfhSILHXDLqL9snbHzDI252ZsQKaZGO67vHc5UeQ4jXa4QkryV2oFWnTwWahFd1m00Umc3O5UL" id="payment-form">
                                            @csrf
                                            <div class='form-row row'>
                                                <div class='col-xs-12 form-group required'>
                                                    <label class='control-label'>Name on Card</label> <input class='form-control' size='4' type='text'>
                                                </div>
                                            </div>
                                            <div class='form-row row'>
                                                <div class='col-xs-12 form-group card required'>
                                                    <label class='control-label'>Card Number</label> <input autocomplete='off' class='form-control card-number' size='20' type='text'>
                                                </div>
                                            </div>
                                            <div class='form-row row'>
                                                <div class='col-xs-12 col-md-4 form-group cvc required'>
                                                    <label class='control-label'>CVC</label> <input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311' size='4' type='text'>
                                                </div>
                                                <div class='col-xs-12 col-md-4 form-group expiration required'>
                                                    <label class='control-label'>Expiration Month</label> <input class='form-control card-expiry-month' placeholder='MM' size='2' type='text'>
                                                </div>
                                                <div class='col-xs-12 col-md-4 form-group expiration required'>
                                                    <label class='control-label'>Expiration Year</label> <input class='form-control card-expiry-year' placeholder='YYYY' size='4' type='text'>
                                                </div>
                                            </div>
                                            <div class='form-row row'>
                                                <div class='col-md-12 error form-group hide'>
                                                    <div class='alert-danger alert'>Please correct the errors and try
                                                        again.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <button class="btn btn-primary btn-lg btn-block" type="submit">Pay Now ($100)</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cart-block mb-3" v-if="storePaymentSettings.IsRazorpayEnabled == 1">
                    <div class="cart-form p-0">
                        <div class="card p-0">
                            <div class="card-header p-0">
                                <a class="collapsed card-link p-3 d-block" data-toggle="collapse" href="#razorpay">
                                    <i class="icofont-globe mr-3"></i> Razorpay
                                </a>
                            </div>
                            <div id="razorpay" class="collapse" data-parent="#accordion">
                                <div class="card-body p-3">
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cart-block mb-3" v-if="storePaymentSettings.IsPaypalEnabled == 1">
                    <div class="cart-form p-0">
                        <div class="card">
                            <div class="card-header p-0">
                                <a class="collapsed card-link p-3 d-block" data-toggle="collapse" href="#paypal">
                                    <i class="icofont-brand-paypal mr-3"></i> Paypal
                                </a>
                            </div>
                            <div id="paypal" class="collapse" data-parent="#accordion">
                                <div class="card-body p-3">
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- </form> -->
            </div>
            <div class="payment-mathod-footer modal-btn-row" v-if="is_COD == true">
                <button class="btn btn-success btn-block btn" @click="placeOrder()">Continue</button>
            </div>
            <div class="payment-mathod-footer modal-btn-row" v-if="is_COD == false">
                <button class="btn btn-success btn-block btn " disabled="">Continue</button>
            </div>
        </div>
    </div>
</div>

<!-- =========Order place successfully model========= -->
<div class="modal fade cart modal-z" id="place-order">
    <div class="modal-dialog modal-dialog-centered m-0">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-heading-content px-3">
                <button class="back-btn item-popup-back-btn p-0 px-0 font-small d-flex align-items-center" data-dismiss="modal"> <i class="icofont-rounded-left back-page"></i> Your Order placed successfully.</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body header-bg cart px-3">
                <div class="input-group mt-0 rounded overflow-hidden">
                    <!-- // <img class="success-img" src="{{asset('assets_store_front/images/success.gif')}}"> -->
                    <i class="fa fa-check-circle fa-7x" style="font-size: 7em;color: lightgreen;margin: 0 auto;"></i>
                </div>
                <div class="mb-2 padding-order-details s-order mt-3" v-if="Object.keys(place_order_detail).length !== 0">
                    <div class="p-3-v2 px-3 pt-3 pb-3 bg-white s-order-block mb-3">
                        <div class="row">
                            <div class="col-6">
                                <h3>Order Details:</h3>
                            </div>
                            <div class="col-6">
                                <div class="text-right" v-if="place_order_detail.call_waiter_enabled == 1">
                                    <a class=" btn btn-square btn-secondary min-width-125 mb-10 order-status-button text-white" @click="order_call_to_waiter(place_order_detail.id)">Call The Waiter
                                    </a>
                                    <span :id="place_order_detail.id" style="display: none;">calling......</span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="flex-auto-v2">
                            <span><b>Customer Name:</b> @{{ place_order_detail.customer_name }}</span><br>
                            <span><b>Customer Mobile:</b> @{{ place_order_detail.customer_phone }}</span>
                            <h6 class="font-w700 mb-0 oderid-color-v2 my-2">@{{ place_order_detail.order_unique_id}}</h6>
                            <span class="text-muted pull-right-v2 my-2 d-block">@{{ moment(String(place_order_detail.created_at)).format('hh:mm A') }} / <b class="text-danger">@{{ moment(String(place_order_detail.created_at)).format('DD-MM-YYYY')}}</b></span>
                            <h6 class="font-w600">Item list:</h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Item Price</th>
                                        <th>Qty</th>
                                        <th>Total Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="orderDetail in place_order_detail.orderDetail">
                                        <td><b>@{{ orderDetail.name }}</b></td>
                                        <td>@{{ orderDetail.price }}</td>
                                        <td>@{{ orderDetail.quantity }}</td>
                                        <td class="color-primary"> @{{ orderDetail.price * orderDetail.quantity }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <div class="content">Sub Total :</div>
                                <div class="content" v-if="place_order_detail.discount != null ">Discount :</div>
                                <div class="content">Serice Charge :</div>
                                <div class="content">Tax :</div>
                                <div class="content"><b>Bill Payable Amount :</b></div>
                            </div>
                            <div class="col-6">
                                <div class="text-right"><b>
                                        <price>Rs. @{{ place_order_detail.sub_total }}</price><br>
                                        <price v-if="place_order_detail.discount !== null ">- Rs. @{{ place_order_detail.discount }}</price><br v-if="place_order_detail.discount !== null">
                                        <price>Rs. @{{ place_order_detail.store_charge }}</price><br>
                                        <price>Rs. @{{ place_order_detail.tax }}</price><br>
                                        <price><b>Rs. @{{ place_order_detail.total }}</b></price>
                                    </b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-btn-row w-100 red-bg px-3 py-3 cart-emapty">
                    <div class="row align-items-center">
                        <div class="col-12 p-0 text-center text-black">
                            <button class="back-btn back-bottom item-popup-back-btn p-0 px-0 font-small d-flex align-items-center" data-dismiss="modal" @click="check_order_status()">Check your order status</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('pageJs')
<script>
    var store_view_id = '{{ $storeDetail->view_id }}'
    var is_vehicle_flag = '{{ $isVehicle }}'
    var selected_table_no = '{{ $table_no }}'
    var categotyItmes = '<?php echo $AllStoreCategory; ?>'
    var categoryArray = categotyItmes

    var RecommendedProductData = '<?php echo $AllStoreRecommendedProduct; ?>'
    var recommendedProductArray = RecommendedProductData
    var search_list_indexUrl = "{{route('store.data')}}";
    var get_order_indexUrl = "{{url('/api/web/store/account/orders')}}";
    var order_call_waiter_indexUrl = "{{url('/api/web/store/waiter/call')}}";
    var apply_coupon_indexUrl = "{{url('/api/web/store/coupon/add')}}";
    var place_order_indexUrl = "{{url('/api/web/store/create/order')}}";
</script>
<script type="text/javascript" src="{{ asset('assets_store_front/js/store_home.js') }}"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
    $(function() {
        var $form = $(".require-validation");
        $('form.require-validation').bind('submit', function(e) {
            var $form = $(".require-validation"),
                inputSelector = ['input[type=email]', 'input[type=password]',
                    'input[type=text]', 'input[type=file]',
                    'textarea'
                ].join(', '),
                $inputs = $form.find('.required').find(inputSelector),
                $errorMessage = $form.find('div.error'),
                valid = true;
            $errorMessage.addClass('hide');
            $('.has-error').removeClass('has-error');
            $inputs.each(function(i, el) {
                var $input = $(el);
                if ($input.val() === '') {
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('hide');
                    e.preventDefault();
                }
            });
            if (!$form.data('cc-on-file')) {
                e.preventDefault();
                Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                }, stripeResponseHandler);
            }
        });

        function stripeResponseHandler(status, response) {
            if (response.error) {
                $('.error')
                    .removeClass('hide')
                    .find('.alert')
                    .text(response.error.message);
            } else {
                /* token contains id, last4, and card type */
                var token = response['id'];
                $form.find('input[type=text]').empty();
                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                $form.get(0).submit();
            }
        }
    });
</script>
@endpush