<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{$account_info != NULL ? $account_info->application_name:"Eatkon"}}</title>
    <!-- ================= Favicon ================== -->
    <!-- Standard -->
    <link rel="shortcut icon" href="{{asset($account_info != NULL ? $account_info->application_logo:"http://placehold.it/144.png/000/fff")}}">
    <!-- Retina iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="144x144" href="{{asset($account_info != NULL ? $account_info->application_logo:"http://placehold.it/144.png/000/fff")}}">
    <!-- Retina iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="114x114" href="{{asset($account_info != NULL ? $account_info->application_logo:"http://placehold.it/144.png/000/fff")}}">
    <!-- Standard iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="72x72" href="{{asset($account_info != NULL ? $account_info->application_logo:"http://placehold.it/144.png/000/fff")}}">
    <!-- Standard iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset($account_info != NULL ? $account_info->application_logo:"http://placehold.it/144.png/000/fff")}}">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="x-ua-compatible" content="IE=10">
    <link rel="stylesheet" href="{{asset('assets_store_front/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets_store_front/css/bootstrap_limitless.min.css')}}">
    <!-- <link rel="stylesheet" href="{{asset('assets_store_front/css/layout.min.css')}}"> -->
    <link rel="stylesheet" href="{{asset('assets_store_front/css/components.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets_store_front/css/font/fontawesome/styles.min.css')}}">
    <link href="{{asset('assets_store_front/css/owl.carousel.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{asset('assets_store_front/css/font/icomoon/styles.css')}}">
    <link rel="stylesheet" href="{{asset('assets_store_front/css/font/flaticons/flaticon.css')}}">
    <link href="{{asset('assets_store_front/css/font/fonts/fonts.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets_store_front/css/custom.css')}}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- start including js files -->
    <script rel="text/javascript" src="{{ asset('assets_store_front/js/vue.min.js') }}"></script>
    <script rel="text/javascript" src="{{ asset('assets_store_front/js/axios.min.js') }}"></script>
    <script type="text/javascript" src="{{asset('assets_store_front/js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets_store_front/js/jquery.ui.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets_store_front/js/bootstrap.bundle.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets_store_front/js/jquery.mmenu.min.all.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets_store_front/js/owl.carousel.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <link rel="stylesheet" href="{{asset('assets_store_front/css/icofont/icofont.min.css')}}">

</head>

<body id="body" class="home_full_bg">
    <div id="app" v-cloak>
        @yield('home_content')
    </div>
    <script type="text/javascript" src="{{asset('assets_store_front/js/custom.js')}}"></script>
    @stack('scripts')
    @stack('pageJs')
</body>


</html>