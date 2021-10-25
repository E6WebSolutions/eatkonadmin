<?php

namespace App\Http\Controllers\Home;

use App\Application;
use App\Category;
use App\Homes;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TranslationService;
use App\Models\Setting;
use App\Models\Store;
use App\Models\StoreSubscription;
use App\Product;
use App\Slider;
use App\StoreSetting;
use App\Testimonial;
use App\Translation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;



class StoreHomeController extends Controller
{


    public function home()
    {
        $transation = new TranslationService();
        $testimonial = Testimonial::all()->sortByDesc('id');
        $stores = Store::limit(4)->get();
        $account_info = Application::all()->first();
        return view('Home.index', [
            'account_info' => $account_info,
            'testimonial' => $testimonial,
            'languages' => $transation->languages(),
            'selected_language' => $transation->selected_language(),
            'stores' => $stores
        ]);
    }


    public function partner_stores()
    {
        $transation = new TranslationService();
        $account_info = Application::all()->first();
        $stores = Store::all()->sortByDesc('id');
        return view('Home.partner_stores', [
            'account_info' => $account_info,
            'languages' => $transation->languages(),
            'selected_language' => $transation->selected_language(),
            'stores' => $stores
        ]);
    }

    public function faq()
    {
        $transation = new TranslationService();
        $account_info = Application::all()->first();
        return view('Home.faq', [
            'account_info' => $account_info,
            'languages' => $transation->languages(),
            'selected_language' => $transation->selected_language(),
        ]);
    }



    public function register()
    {
        $transation = new TranslationService();
        $subscription = StoreSubscription::all()->where('is_active', 1);
        $account_info = Application::all()->first();
        return view('Home.register', [
            'account_info' => $account_info,
            'subscription' => $subscription,
            'languages' => $transation->languages(),
            'selected_language' => $transation->selected_language(),
        ]);
    }

    public function pricing()
    {
        $transation = new TranslationService();
        $subscription = StoreSubscription::all()->where('is_active', 1);
        $account_info = Application::all()->first();
        $testimonial = Testimonial::all()->sortByDesc('id');
        return view('Home.pricing', [
            'account_info' => $account_info,
            'subscription' => $subscription,
            'languages' => $transation->languages(),
            'selected_language' => $transation->selected_language(),
            'testimonial' => $testimonial
        ]);
    }

    public function privacy()
    {
        $transation = new TranslationService();
        $privacy = Setting::all()->where('key', 'PrivacyPolicy')->first()->value;
        $home = Homes::all();
        $account_info = Application::all()->first();
        return view('Home.privacy', [
            'account_info' => $account_info,
            'home' => $home,
            'privacy' => $privacy,
            'languages' => $transation->languages(),
            'selected_language' => $transation->selected_language()
        ]);
    }


    public function about()
    {
        $transation = new TranslationService();
        $about = Setting::all()->where('key', 'Abouts')->first()->value;
        $home = Homes::all();
        $account_info = Application::all()->first();
        return view('Home.about', [
            'account_info' => $account_info,
            'home' => $home,
            'about' => $about,
            'languages' => $transation->languages(),
            'selected_language' => $transation->selected_language()

        ]);
    }


    public function termsandcondition()
    {
        $transation = new TranslationService();
        $tearms = Setting::all()->where('key', 'TermsandCondition')->first()->value;
        $home = Homes::all();
        $account_info = Application::all()->first();
        return view('Home.termsandcondition', [
            'account_info' => $account_info,
            'home' => $home,
            'tearms' => $tearms,
            'languages' => $transation->languages(),
            'selected_language' => $transation->selected_language()

        ]);
    }

    public function refund()
    {
        $transation = new TranslationService();
        $refund = Setting::all()->where('key', 'Refund')->first()->value;
        $home = Homes::all();
        $account_info = Application::all()->first();
        return view('Home.refund', [
            'account_info' => $account_info,
            'home' => $home,
            'refund' => $refund,
            'languages' => $transation->languages(),
            'selected_language' => $transation->selected_language()

        ]);
    }


    public function index(Request $request, $view_id)
    {

        $account_info = Application::all()->first();
        $custom_css = Setting::find(18);
        
        $isVehicle = false;
        if($request->has('vehicle')){
            $isVehicle = true;
        }
        $table_no = 0;
        if($request->has('table')){
            $table_no = $request->table;
        }
        

        if (Store::all()->where('view_id', '=', $view_id)->count() == 0) {
            abort(404);
        }
        if (Store::all()->where('view_id', '=', $view_id)->where('is_visible', '=', 1)->where('subscription_end_date', '>=', date('Y-m-d'))->count() == 0)
            // return view('Home.404',[
            //     'account_info' =>$account_info,
            // ]);
            return view('StoreFront.404', [
                'account_info' => $account_info,
            ]);
        $store = Store::all()->where('view_id', '=', $view_id)->first();
        $store_id  = $store['id'];
        $store_name  = $store['store_name'];
        $description  = $store['description'];
        $AllStoreCategory = Category::where(array('store_id' => $store_id, 'is_active' => 1))->get();
        $AllStoreRecommendedProduct = Product::where(array('store_id' => $store->id, 'is_recommended' => 1, 'is_active' => 1))->get();
        // $finalData = array();
        foreach($AllStoreCategory as $_AllStoreCategory){
            $_AllStoreCategory->name = str_replace("'"," ",$_AllStoreCategory->name);
            $storeProduct = Product::where(array('store_id' => $store_id, 'category_id' => $_AllStoreCategory->id, 'is_active' => 1))->get();
            foreach($storeProduct as $_storeProduct){
                $_storeProduct->name = str_replace("'"," ",$_storeProduct->name);
                $_storeProduct->description = str_replace("'"," ",$_storeProduct->description);
            }
            $_AllStoreCategory->AllItems = $storeProduct;
            // $finalData[] = $_AllStoreCategory;
        }
        foreach($AllStoreRecommendedProduct as $_AllStoreRecommendedProduct){
            $_AllStoreRecommendedProduct->name = str_replace("'"," ",$_AllStoreRecommendedProduct->name);
            $_AllStoreRecommendedProduct->description = str_replace("'"," ",$_AllStoreRecommendedProduct->description);
        }
        // echo '<pre>';
        // print_r($AllStoreRecommendedProduct); die;
        // return view('Home.show_store',[
        //     'account_info' =>$account_info,
        //     'custom_css'=>$custom_css,
        //     'store_name'=>$store_name
        //     ]);
        return view('StoreFront.show_store', [
            'account_info' => $account_info,
            'custom_css' => $custom_css,
            'store_name' => $store_name,
            'AllStoreCategory' => json_encode($AllStoreCategory),
            'AllStoreRecommendedProduct' => json_encode($AllStoreRecommendedProduct),
            'storeDetail' => $store,
            'isVehicle' => $isVehicle,
            'table_no' => $table_no
        ]);
    }


    public function indexjs($view_id)
    {
        if (Store::all()->where('view_id', '=', $view_id)->count() == 0) {
            abort(404);
        }
        if (Store::all()->where('view_id', '=', $view_id)->where('is_visible', '=', 1)->count() == 0)
            return view('Home.404');

        $customcss = Setting::all();

        $store = Store::all()->where('view_id', '=', $view_id)->first();
        $store_id  = $store['id'];
        $store_name  = $store['store_name'];
        $description  = $store['description'];
        $sliders = Slider::all()->where('is_visible', '=', 1);
        $recommended = Product::all()->where('store_id', '=', $store_id)
            ->where('is_recommended', '=', 1)
            ->where('is_active', '=', 1)->sortBy('name');

        $categories = Category::all()->where('store_id', '=', $store_id)
            ->where('is_active', '=', 1)->sortBy('name');

        $products = Product::all()->where('store_id', '=', $store_id)
            ->where('is_active', '=', 1)->sortBy('name');
        $account_info = Application::all()->first();

        return view('Home.show_store_old', [
            'recommended' => $recommended,
            'categories' => $categories,
            'products' => $products,
            'account_info' => $account_info,
            'store_name' => $store_name,
            'description' => $description,
            'sliders' => $sliders,
            'customcss' => $customcss,
        ]);
    }
    public function RegisterNewStore(Request $request)
    {
        $data = request()->validate([
            'store_name' => 'required',
            'email' => ['required', Rule::unique('stores', 'email')],
            'password' => 'required',
            'phone' => 'required',
            'address' => '',
            'description' => '',
            'theme_id' => '',
        ]);
        $data['password'] = Hash::make($data['password']);
        $data['logo_url'] = "NaN";
        $data['view_id'] = sha1(time());
        $plan = StoreSubscription::all()->where('id', '=', $request->plan)->first();
        if ($plan->price == 0) {
            $data['subscription_end_date'] = date('Y-m-d', strtotime(date('Y-m-d') . ' + ' . $plan->days . ' days'));
        } else {
            $data['subscription_end_date'] = date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 days'));
        }
        if (Store::create($data))
            return redirect(route('store.login'))->with("MSG", "Account Created successfully")->with("TYPE", "success");
    }
    public function product_details()
    {
        //return 1;

        return view('Home.show_store');
    }

    public function install_completed()
    {

        unlink(base_path('install'));
        return view('install.completed');
    }

    /**
     * added by monika
     * ajax call with vuejs
     */
    public function searchResult(Request $request)
    {
        $searchTxt = '';
        $storeData = Store::all()->where('view_id', '=', $request->view_id)->first();

        $storePaymentSettings = StoreSetting::all()->where('store_id', '=', $storeData->id)->first();

        $query = Category::whereRaw('1=1');
        
        if($request->search && $request->search != ''){
            $search = '%' . $request->search . '%';
            $query->where('name', 'LIKE', $search);
        }
        $query->where(array('store_id' => $storeData->id, 'is_active' => 1));
        $AllStoreCategory = $query->get();

        foreach($AllStoreCategory as $_AllStoreCategory){
            $_AllStoreCategory->image_url = asset($_AllStoreCategory->image_url);
            $_AllStoreCategory->name = str_replace("'"," ",$_AllStoreCategory->name);
            $storeProduct = Product::where(array('store_id' => $storeData->id, 'category_id' => $_AllStoreCategory->id, 'is_active' => 1))->get();
            foreach($storeProduct as $_storeProduct){
                $_storeProduct->image_url = asset($_storeProduct->image_url);
            }
            $_AllStoreCategory->AllItems = $storeProduct;
        }

        $qry = Product::whereRaw('1=1');
        $qry->where(array('store_id' => $storeData->id, 'is_recommended' => 1, 'is_active' => 1))->limit(12)->get();
        if($request->search && $request->search != ''){
            $search = '%' . $request->search . '%';
            $qry->where('name', 'LIKE', $search);
        }
        $AllStoreRecommendedProduct = $qry->get();
        foreach($AllStoreRecommendedProduct as $_AllStoreRecommendedProduct){
            $_AllStoreRecommendedProduct->image_url = asset($_AllStoreRecommendedProduct->image_url);
        }
        
        $AllCateData = Category::where(array('store_id' => $storeData->id, 'is_active' => 1))->get();
        $AllStoreItem = array();
        foreach ($AllCateData as $_AllCateData) {
            $qury = Product::whereRaw('1=1');
            $qury->where(array('store_id' => $storeData->id, 'category_id' => $_AllCateData->id, 'is_active' => 1));
            if($request->search && $request->search != ''){
                $search = '%' . $request->search . '%';
                $qury->where('name', 'LIKE', $search);
            }
            $storeProduct = $qury->get();
            foreach($storeProduct as $_storeProduct){
                $_storeProduct->image_url = asset($_storeProduct->image_url);
            }
            $_AllCateData->AllItems = $storeProduct;
            $AllStoreItem[] = $_AllCateData;
        }
        $resultData = array(
            'storeData' => $storeData,
            'AllStoreCategory' => $AllStoreCategory,
            'AllStoreRecommendedProduct' => $AllStoreRecommendedProduct,
            'AllStoreItem' => $AllStoreItem,
            'storePaymentSettings' => $storePaymentSettings
        );

        return $resultData;
    }
}
