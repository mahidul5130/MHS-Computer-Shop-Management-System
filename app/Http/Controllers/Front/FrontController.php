<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use Laravel\Socialite\Facades\Socialite;
use App\Library\SslCommerz\SslCommerzNotification;


use Crypt;
use Mail;

class FrontController extends Controller
{
    public function index(Request $request)
    {
        $result['home_categories'] = DB::table('categories')
            ->where(['status' => 1])
            ->where(['is_home' => 1])
            ->get();


        foreach ($result['home_categories'] as $list) {
            $result['home_categories_product'][$list->id] =
                DB::table('products')
                ->where(['status' => 1])
                ->where(['category_id' => $list->id])
                ->get();

            foreach ($result['home_categories_product'][$list->id] as $list1) {
                $result['home_product_attr'][$list1->id] =
                    DB::table('products_attr')
                    ->leftJoin('rams', 'rams.id', '=', 'products_attr.size_id')
                    ->leftJoin('colors', 'colors.id', '=', 'products_attr.color_id')
                    ->where(['products_attr.products_id' => $list1->id])
                    ->get();
            }
        }

        $result['home_brand'] = DB::table('brands')
            ->where(['status' => 1])
            ->where(['is_home' => 1])
            ->get();


        $result['home_featured_product'][$list->id] =
            DB::table('products')
            ->where(['status' => 1])
            ->where(['is_featured' => 1])
            ->get();

        foreach ($result['home_featured_product'][$list->id] as $list1) {
            $result['home_featured_product_attr'][$list1->id] =
                DB::table('products_attr')
                ->leftJoin('rams', 'rams.id', '=', 'products_attr.size_id')
                ->leftJoin('colors', 'colors.id', '=', 'products_attr.color_id')
                ->where(['products_attr.products_id' => $list1->id])
                ->get();
        }

        $result['home_tranding_product'][$list->id] =
            DB::table('products')
            ->where(['status' => 1])
            ->where(['is_tranding' => 1])
            ->get();

        foreach ($result['home_tranding_product'][$list->id] as $list1) {
            $result['home_tranding_product_attr'][$list1->id] =
                DB::table('products_attr')
                ->leftJoin('rams', 'rams.id', '=', 'products_attr.size_id')
                ->leftJoin('colors', 'colors.id', '=', 'products_attr.color_id')
                ->where(['products_attr.products_id' => $list1->id])
                ->get();
        }

        $result['home_discounted_product'][$list->id] =
            DB::table('products')
            ->where(['status' => 1])
            ->where(['is_discounted' => 1])
            ->get();

        foreach ($result['home_discounted_product'][$list->id] as $list1) {
            $result['home_discounted_product_attr'][$list1->id] =
                DB::table('products_attr')
                ->leftJoin('rams', 'rams.id', '=', 'products_attr.size_id')
                ->leftJoin('colors', 'colors.id', '=', 'products_attr.color_id')
                ->where(['products_attr.products_id' => $list1->id])
                ->get();
        }

        $result['home_banner'] = DB::table('home_banners')
            ->where(['status' => 1])
            ->get();

        return view('front.index', $result);
    }

    public function category(Request $request, $slug)
    {
        $sort = "";
        $sort_txt = "";
        $filter_price_start = "";
        $filter_price_end = "";
        $color_filter = "";
        $colorFilterArr = [];
        if ($request->get('sort') !== null) {
            $sort = $request->get('sort');
        }

        $query = DB::table('products');
        $query = $query->leftJoin('categories', 'categories.id', '=', 'products.category_id');
        $query = $query->leftJoin('products_attr', 'products.id', '=', 'products_attr.products_id');
        $query = $query->where(['products.status' => 1]);
        $query = $query->where(['categories.category_slug' => $slug]);
        if ($sort == 'name') {
            $query = $query->orderBy('products.name', 'asc');
            $sort_txt = "Product Name";
        }
        if ($sort == 'date') {
            $query = $query->orderBy('products.id', 'desc');
            $sort_txt = "Date";
        }
        if ($sort == 'price_desc') {
            $query = $query->orderBy('products_attr.price', 'desc');
            $sort_txt = "Price - DESC";
        }
        if ($sort == 'price_asc') {
            $query = $query->orderBy('products_attr.price', 'asc');
            $sort_txt = "Price - ASC";
        }
        if ($request->get('filter_price_start') !== null && $request->get('filter_price_end') !== null) {
            $filter_price_start = $request->get('filter_price_start');
            $filter_price_end = $request->get('filter_price_end');

            if ($filter_price_start > 0 && $filter_price_end > 0) {
                $query = $query->whereBetween('products_attr.price', [$filter_price_start, $filter_price_end]);
            }
        }

        if ($request->get('color_filter') !== null) {
            $color_filter = $request->get('color_filter');
            $colorFilterArr = explode(":", $color_filter);
            $colorFilterArr = array_filter($colorFilterArr);

            $query = $query->where(['products_attr.color_id' => $request->get('color_filter')]);
        }

        $query = $query->distinct()->select('products.*');
        $query = $query->get();
        $result['product'] = $query;

        foreach ($result['product'] as $list1) {

            $query1 = DB::table('products_attr');
            $query1 = $query1->leftJoin('rams', 'rams.id', '=', 'products_attr.size_id');
            $query1 = $query1->leftJoin('colors', 'colors.id', '=', 'products_attr.color_id');
            $query1 = $query1->where(['products_attr.products_id' => $list1->id]);
            $query1 = $query1->get();
            $result['product_attr'][$list1->id] = $query1;
        }

        $result['colors'] = DB::table('colors')
            ->where(['status' => 1])
            ->get();


        $result['categories_left'] = DB::table('categories')
            ->where(['status' => 1])
            ->get();

        $result['slug'] = $slug;
        $result['sort'] = $sort;
        $result['sort_txt'] = $sort_txt;
        $result['filter_price_start'] = $filter_price_start;
        $result['filter_price_end'] = $filter_price_end;
        $result['color_filter'] = $color_filter;
        $result['colorFilterArr'] = $colorFilterArr;
        return view('front.category', $result);
    }
    public function product(Request $request, $slug)
    {
        $result['product'] =
            DB::table('products')
            ->where(['status' => 1])
            ->where(['slug' => $slug])
            ->get();

        foreach ($result['product'] as $list1) {
            $result['product_attr'][$list1->id] =
                DB::table('products_attr')
                ->leftJoin('rams', 'rams.id', '=', 'products_attr.size_id')
                ->leftJoin('colors', 'colors.id', '=', 'products_attr.color_id')
                ->where(['products_attr.products_id' => $list1->id])
                ->get();
        }

        foreach ($result['product'] as $list1) {
            $result['product_images'][$list1->id] =
                DB::table('product_images')
                ->where(['product_images.products_id' => $list1->id])
                ->get();
        }
        $result['related_product'] =
            DB::table('products')
            ->where(['status' => 1])
            ->where('slug', '!=', $slug)
            ->where(['category_id' => $result['product'][0]->category_id])
            ->get();
        foreach ($result['related_product'] as $list1) {
            $result['related_product_attr'][$list1->id] =
                DB::table('products_attr')
                ->leftJoin('rams', 'rams.id', '=', 'products_attr.size_id')
                ->leftJoin('colors', 'colors.id', '=', 'products_attr.color_id')
                ->where(['products_attr.products_id' => $list1->id])
                ->get();
        }

        $result['product_review'] =
            DB::table('product_review')
            ->leftJoin('customers', 'customers.id', '=', 'product_review.customer_id')
            ->where(['product_review.products_id' => $result['product'][0]->id])
            ->where(['product_review.status' => 1])
            ->orderBy('product_review.added_on', 'desc')
            ->select('product_review.rating', 'product_review.review', 'product_review.added_on', 'customers.name')
            ->get();
        //prx($result['product_review']);

        return view('front.product', $result);
    }

    public function add_to_cart(Request $request)
    {
        if ($request->session()->has('FRONT_USER_LOGIN')) {
            $uid = $request->session()->get('FRONT_USER_ID');
            $user_type = "Reg";
        } else {
            $uid = getUserTempId();
            $user_type = "Not-Reg";
        }

        $size_id = $request->post('size_id');
        $color_id = $request->post('color_id');
        $pqty = $request->post('pqty');
        $product_id = $request->post('product_id');



        $result = DB::table('products_attr')
            ->select('products_attr.id')
            ->leftJoin('rams', 'rams.id', '=', 'products_attr.size_id')
            ->leftJoin('colors', 'colors.id', '=', 'products_attr.color_id')
            ->where(['products_id' => $product_id])
            ->where(['rams.size' => $size_id])
            ->where(['colors.color' => $color_id])
            ->get();
        $product_attr_id = $result[0]->id;

        $getAvaliableQty = getAvaliableQty($product_id, $product_attr_id);
        // $finalAvaliable = $getAvaliableQty[0]->pqty - $getAvaliableQty[0]->qty;
        // if ($pqty > $finalAvaliable) {
        //     return response()->json(['msg' => "not_avaliable", 'data' => "Only $finalAvaliable left"]);
        // }

        $check = DB::table('cart')
            ->where(['user_id' => $uid])
            ->where(['user_type' => $user_type])
            ->where(['product_id' => $product_id])
            ->where(['product_attr_id' => $product_attr_id])
            ->get();
        if (isset($check[0])) {
            $update_id = $check[0]->id;
            if ($pqty == 0) {
                DB::table('cart')
                    ->where(['id' => $update_id])
                    ->delete();
                $msg = "removed";
            } else {
                DB::table('cart')
                    ->where(['id' => $update_id])
                    ->update(['qty' => $pqty]);
                $msg = "updated";
            }
        } else {
            $id = DB::table('cart')->insertGetId([
                'user_id' => $uid,
                'user_type' => $user_type,
                'product_id' => $product_id,
                'product_attr_id' => $product_attr_id,
                'qty' => $pqty,
                'added_on' => date('Y-m-d h:i:s')
            ]);
            $msg = "added";
        }
        $result = DB::table('cart')
            ->leftJoin('products', 'products.id', '=', 'cart.product_id')
            ->leftJoin('products_attr', 'products_attr.id', '=', 'cart.product_attr_id')
            ->leftJoin('rams', 'rams.id', '=', 'products_attr.size_id')
            ->leftJoin('colors', 'colors.id', '=', 'products_attr.color_id')
            ->where(['user_id' => $uid])
            ->where(['user_type' => $user_type])
            ->select('cart.qty', 'products.name', 'products.image', 'rams.size', 'colors.color', 'products_attr.price', 'products.slug', 'products.id as pid', 'products_attr.id as attr_id')
            ->get();
        return response()->json(['msg' => $msg, 'data' => $result, 'totalItem' => count($result)]);
    }

    public function cart(Request $request)
    {
        if ($request->session()->has('FRONT_USER_LOGIN')) {
            $uid = $request->session()->get('FRONT_USER_ID');
            $user_type = "Reg";
        } else {
            $uid = getUserTempId();
            $user_type = "Not-Reg";
        }
        $result['list'] = DB::table('cart')
            ->leftJoin('products', 'products.id', '=', 'cart.product_id')
            ->leftJoin('products_attr', 'products_attr.id', '=', 'cart.product_attr_id')
            ->leftJoin('rams', 'rams.id', '=', 'products_attr.size_id')
            ->leftJoin('colors', 'colors.id', '=', 'products_attr.color_id')
            ->where(['user_id' => $uid])
            ->where(['user_type' => $user_type])
            ->select('cart.qty', 'products.name', 'products.image', 'rams.size', 'colors.color', 'products_attr.price', 'products.slug', 'products.id as pid', 'products_attr.id as attr_id')
            ->get();
        return view('front.cart', $result);
    }

    public function search(Request $request, $str)
    {
        $result['product'] =
            $query = DB::table('products');
        $query = $query->leftJoin('categories', 'categories.id', '=', 'products.category_id');
        $query = $query->leftJoin('products_attr', 'products.id', '=', 'products_attr.products_id');
        $query = $query->where(['products.status' => 1]);
        $query = $query->where('name', 'like', "%$str%");
        $query = $query->orwhere('model', 'like', "%$str%");
        $query = $query->orwhere('short_desc', 'like', "%$str%");
        $query = $query->orwhere('desc', 'like', "%$str%");
        $query = $query->orwhere('keywords', 'like', "%$str%");
        $query = $query->orwhere('technical_specification', 'like', "%$str%");
        $query = $query->distinct()->select('products.*');
        $query = $query->get();
        $result['product'] = $query;

        foreach ($result['product'] as $list1) {

            $query1 = DB::table('products_attr');
            $query1 = $query1->leftJoin('rams', 'rams.id', '=', 'products_attr.size_id');
            $query1 = $query1->leftJoin('colors', 'colors.id', '=', 'products_attr.color_id');
            $query1 = $query1->where(['products_attr.products_id' => $list1->id]);
            $query1 = $query1->get();
            $result['product_attr'][$list1->id] = $query1;
        }

        return view('front.search', $result);
    }

    public function registration(Request $request)
    {
        if ($request->session()->has('FRONT_USER_LOGIN') != null) {
            return redirect('/');
        }

        $result = [];
        return view('front.registration', $result);
    }

    public function registration_process(Request $request)
    {
        $valid = Validator::make($request->all(), [
            "name" => 'required',
            "email" => 'required|email|unique:customers,email',
            "password" => 'required',
            "mobile" => 'required|numeric|digits:11',
        ]);

        if (!$valid->passes()) {
            return response()->json(['status' => 'error', 'error' => $valid->errors()->toArray()]);
        } else {
            $rand_id = rand(111111111, 999999999);
            $arr = [
                "name" => $request->name,
                "email" => $request->email,
                "password" => Crypt::encrypt($request->password),
                "mobile" => $request->mobile,
                "status" => 1,
                "is_verify" => 0,
                "is_forgot_password" => 0,
                "rand_id" => $rand_id,
                "created_at" => date('Y-m-d h:i:s'),
                "updated_at" => date('Y-m-d h:i:s')
            ];
            $query = DB::table('customers')->insert($arr);
            if ($query) {

                $data = ['name' => $request->name, 'rand_id' => $rand_id];
                $user['to'] = $request->email;
                Mail::send('front/email_verification', $data, function ($messages) use ($user) {
                    $messages->to($user['to']);
                    $messages->subject('Email Id Verification');
                });

                return response()->json(['status' => 'success', 'msg' => "Registration successfully. Please check your email id for verification"]);
            }
        }
    }

    public function login_process(Request $request)
    {

        $result = DB::table('customers')
            ->where(['email' => $request->str_login_email])
            ->get();

        if (isset($result[0])) {
            $db_pwd = Crypt::decrypt($result[0]->password);
            $status = $result[0]->status;
            $is_verify = $result[0]->is_verify;

            if ($is_verify == 0) {
                return response()->json(['status' => "error", 'msg' => 'Please verify your email id']);
            }
            if ($status == 0) {
                return response()->json(['status' => "error", 'msg' => 'Your account has been deactivated']);
            }

            if ($db_pwd == $request->str_login_password) {

                if ($request->rememberme === null) {
                    setcookie('login_email', $request->str_login_email, 100);
                    setcookie('login_pwd', $request->str_login_password, 100);
                } else {
                    setcookie('login_email', $request->str_login_email, time() + 60 * 60 * 24 * 100);
                    setcookie('login_pwd', $request->str_login_password, time() + 60 * 60 * 24 * 100);
                }

                $request->session()->put('FRONT_USER_LOGIN', true);
                $request->session()->put('FRONT_USER_ID', $result[0]->id);
                $request->session()->put('FRONT_USER_NAME', $result[0]->name);
                $status = "success";
                $msg = "";

                $getUserTempId = getUserTempId();
                DB::table('cart')
                    ->where(['user_id' => $getUserTempId, 'user_type' => 'Not-Reg'])
                    ->update(['user_id' => $result[0]->id, 'user_type' => 'Reg']);
            } else {
                $status = "error";
                $msg = "Please enter valid password";
            }
        } else {
            $status = "error";
            $msg = "Please enter valid email id";
        }
        return response()->json(['status' => $status, 'msg' => $msg]);
        //$request->password
    }

    // Google login
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Google callback
    public function handleGoogleCallback()
    {

        $user = Socialite::driver('google')->user();

        $this->_registerOrLoginUser($user);

        // Return home after login
        return redirect('/');
    }

    protected function _registerOrLoginUser($data)
    {
        $user = DB::table('customers')->where('email', '=', $data->email)->first();
        if (!$user) {
            $rand_id = rand(111111111, 999999999);
            $rand_pass = rand(111111111, 999999999);
            $arr = [
                "name" => $data->name,
                "email" => $data->email,
                "password" => Crypt::encrypt($rand_pass),
                // "mobile" => $data->mobile,
                "address" => $data->address,
                "status" => 1,
                "is_verify" => 1,
                "is_forgot_password" => 0,
                "rand_id" => $rand_id,
                "created_at" => date('Y-m-d h:i:s'),
                "updated_at" => date('Y-m-d h:i:s')
            ];
            $query = DB::table('customers')->insert($arr);

            $info = ['name' => $data->name, 'password' => $rand_pass];
            Mail::send('front/password_send', $info, function ($messages) use ($data) {
                $messages->to($data->email);
                $messages->subject('New Password');
            });
        }

        $result = DB::table('customers')
            ->where(['email' => $data->email])
            ->get()
            ->first();


        // if ($remember === null) {
        setcookie('login_email', (string)$data->email, 100);
        setcookie('login_pwd', (string)Crypt::decrypt($result->password), 100);
        // } else {
        //     setcookie('login_email', (string)$data->email, time() + 60 * 60 * 24 * 100);
        //     setcookie('login_pwd', (string)Crypt::decrypt($result->password), time() + 60 * 60 * 24 * 100);
        // }


        session()->put('FRONT_USER_LOGIN', true);
        session()->put('FRONT_USER_ID', $result->id);
        session()->put('FRONT_USER_NAME', $result->name);
        $status = "success";
        $msg = "";
        // prx(gettype((string)$data->email));
        // prx((string)Crypt::decrypt($result->password));

        // setcookie('login_email', 'adobe.scrt@gmail.com', time() + 60 * 60 * 24 * 100);
        // setcookie('login_pwd','243269948', time() + 60 * 60 * 24 * 100);
        // prx($result);

        // Auth::login($user);
        return response()->json(['status' => $status, 'msg' => $msg]);
    }

    public function email_verification(Request $request, $id)
    {
        $result = DB::table('customers')
            ->where(['rand_id' => $id])
            ->where(['is_verify' => 0])
            ->get();

        if (isset($result[0])) {
            DB::table('customers')
                ->where(['id' => $result[0]->id])
                ->update(['is_verify' => 1, 'rand_id' => '']);
            return view('front.verification');
        } else {
            return redirect('/');
        }
    }


    public function forgot_password(Request $request)
    {

        $result = DB::table('customers')
            ->where(['email' => $request->str_forgot_email])
            ->get();
        $rand_id = rand(111111111, 999999999);
        if (isset($result[0])) {

            DB::table('customers')
                ->where(['email' => $request->str_forgot_email])
                ->update(['is_forgot_password' => 1, 'rand_id' => $rand_id]);

            $data = ['name' => $result[0]->name, 'rand_id' => $rand_id];
            $user['to'] = $request->str_forgot_email;
            Mail::send('front/forgot_email', $data, function ($messages) use ($user) {
                $messages->to($user['to']);
                $messages->subject("Forgot Password");
            });
            return response()->json(['status' => 'success', 'msg' => 'Please check your email for password']);
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Email id not registered']);
        }
    }


    public function forgot_password_change(Request $request, $id)
    {
        $result = DB::table('customers')
            ->where(['rand_id' => $id])
            ->where(['is_forgot_password' => 1])
            ->get();

        if (isset($result[0])) {
            $request->session()->put('FORGOT_PASSWORD_USER_ID', $result[0]->id);

            return view('front.forgot_password_change');
        } else {
            return redirect('/');
        }
    }

    public function forgot_password_change_process(Request $request)
    {
        DB::table('customers')
            ->where(['id' => $request->session()->get('FORGOT_PASSWORD_USER_ID')])
            ->update(
                [
                    'is_forgot_password' => 0,
                    'password' => Crypt::encrypt($request->password),
                    'rand_id' => ''
                ]
            );
        return response()->json(['status' => 'success', 'msg' => 'Password changed']);
    }

    public function checkout(Request $request)
    {
        $result['cart_data'] = getAddToCartTotalItem();

        if (isset($result['cart_data'][0])) {

            if ($request->session()->has('FRONT_USER_LOGIN')) {
                $uid = $request->session()->get('FRONT_USER_ID');
                $customer_info = DB::table('customers')
                    ->where(['id' => $uid])
                    ->get();
                $result['customers']['name'] = $customer_info[0]->name;
                $result['customers']['email'] = $customer_info[0]->email;
                $result['customers']['mobile'] = $customer_info[0]->mobile;
                $result['customers']['address'] = $customer_info[0]->address;
                $result['customers']['city'] = $customer_info[0]->city;
                $result['customers']['state'] = $customer_info[0]->state;
                $result['customers']['zip'] = $customer_info[0]->zip;
            } else {
                $result['customers']['name'] = '';
                $result['customers']['email'] = '';
                $result['customers']['mobile'] = '';
                $result['customers']['address'] = '';
                $result['customers']['city'] = '';
                $result['customers']['state'] = '';
                $result['customers']['zip'] = '';
            }

            return view('front.checkout', $result);
        } else {
            return redirect('/');
        }
    }

    public function apply_coupon_code(Request $request)
    {
        $arr = apply_coupon_code($request->coupon_code);
        $arr = json_decode($arr, true);

        return response()->json(['status' => $arr['status'], 'msg' => $arr['msg'], 'totalPrice' => $arr['totalPrice']]);
    }

    public function remove_coupon_code(Request $request)
    {
        $totalPrice = 0;
        $result = DB::table('coupons')
            ->where(['code' => $request->coupon_code])
            ->get();
        $getAddToCartTotalItem = getAddToCartTotalItem();
        $totalPrice = 0;
        foreach ($getAddToCartTotalItem as $list) {
            $totalPrice = $totalPrice + ($list->qty * $list->price);
        }

        return response()->json(['status' => 'success', 'msg' => 'Coupon code removed', 'totalPrice' => $totalPrice]);
    }

    public function place_order(Request $request)
    {
        $payment_url = '';
        $rand_id = rand(111111111, 999999999);

        if ($request->session()->has('FRONT_USER_LOGIN')) {
        } else {
            $valid = Validator::make($request->all(), [
                "email" => 'required|email|unique:customers,email'
            ]);

            if (!$valid->passes()) {
                return response()->json(['status' => 'error', 'msg' => "The email has already been taken"]);
            } else {
                $arr = [
                    "name" => $request->name,
                    "email" => $request->email,
                    "address" => $request->address,
                    "city" => $request->city,
                    "state" => $request->state,
                    "zip" => $request->zip,
                    "password" => Crypt::encrypt($rand_id),
                    "mobile" => $request->mobile,
                    "status" => 1,
                    "is_verify" => 1,
                    "rand_id" => $rand_id,
                    "created_at" => date('Y-m-d h:i:s'),
                    "updated_at" => date('Y-m-d h:i:s'),
                    "is_forgot_password" => 0
                ];

                $user_id = DB::table('customers')->insertGetId($arr);
                $request->session()->put('FRONT_USER_LOGIN', true);
                $request->session()->put('FRONT_USER_ID', $user_id);
                $request->session()->put('FRONT_USER_NAME', $request->name);

                $data = ['name' => $request->name, 'password' => $rand_id];
                $user['to'] = $request->email;
                Mail::send('front/password_send', $data, function ($messages) use ($user) {
                    $messages->to($user['to']);
                    $messages->subject('New Password');
                });

                $getUserTempId = getUserTempId();
                DB::table('cart')
                    ->where(['user_id' => $getUserTempId, 'user_type' => 'Not-Reg'])
                    ->update(['user_id' => $user_id, 'user_type' => 'Reg']);
            }
        }
        $coupon_value = 0;
        if ($request->coupon_code != '') {
            $arr = apply_coupon_code($request->coupon_code);
            $arr = json_decode($arr, true);
            if ($arr['status'] == 'success') {
                $coupon_value = $arr['coupon_code_value'];
            } else {
                return response()->json(['status' => 'false', 'msg' => $arr['msg']]);
            }
        }


        $uid = $request->session()->get('FRONT_USER_ID');
        $totalPrice = 0;
        $getAddToCartTotalItem = getAddToCartTotalItem();
        foreach ($getAddToCartTotalItem as $list) {
            $totalPrice = $totalPrice + ($list->qty * $list->price);
        }
        $arr = [
            "customers_id" => $uid,
            "name" => $request->name,
            "email" => $request->email,
            "mobile" => $request->mobile,
            "address" => $request->address,
            "city" => $request->city,
            "state" => $request->state,
            "pincode" => $request->zip,
            "coupon_code" => $request->coupon_code,
            "coupon_value" => $coupon_value,
            "payment_type" => $request->payment_type,
            "payment_status" => "Pending",
            "total_amt" => $totalPrice,
            "order_status" => 1,
            "added_on" => date('Y-m-d h:i:s')
        ];
        $order_id = DB::table('orders')->insertGetId($arr);

        if ($order_id > 0) {
            foreach ($getAddToCartTotalItem as $list) {
                $prductDetailArr['product_id'] = $list->pid;
                $prductDetailArr['products_attr_id'] = $list->attr_id;
                $prductDetailArr['price'] = $list->price;
                $prductDetailArr['qty'] = $list->qty;
                $prductDetailArr['orders_id'] = $order_id;
                DB::table('orders_details')->insert($prductDetailArr);
            }

            if ($request->payment_type == 'Gateway') {
                $final_amt = $totalPrice - $coupon_value;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://test.instamojo.com/api/1.1/payment-requests/');
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt(
                    $ch,
                    CURLOPT_HTTPHEADER,
                    array(
                        "X-Api-Key:KEY",
                        "X-Auth-Token:TOKEN"
                    )
                );
                $payload = array(
                    'purpose' => 'Buy Product',
                    'amount' => $final_amt,
                    'phone' => $request->mobile,
                    'buyer_name' => $request->name,
                    'redirect_url' => 'http://127.0.0.1:8000/instamojo_payment_redirect',
                    'send_email' => true,
                    'send_sms' => true,
                    'email' => $request->email,
                    'allow_repeated_payments' => false
                );
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
                $response = curl_exec($ch);
                curl_close($ch);
                $response = json_decode($response);
                if (isset($response->payment_request->id)) {
                    $txn_id = $response->payment_request->id;
                    DB::table('orders')
                        ->where(['id' => $order_id])
                        ->update(['txn_id' => $txn_id]);
                    $payment_url = $response->payment_request->longurl;
                } else {
                    $msg = "";
                    foreach ($response->message as $key => $val) {
                        $msg .= strtoupper($key) . ": " . $val[0] . '<br/>';
                    }
                    return response()->json(['status' => 'error', 'msg' => $msg, 'payment_url' => '']);
                }
            }
            DB::table('cart')->where(['user_id' => $uid, 'user_type' => 'Reg'])->delete();
            $request->session()->put('ORDER_ID', $order_id);

            $status = "success";
            $msg = "Order placed";
        } else {
            $status = "false";
            $msg = "Please try after sometime";
        }
        return response()->json(['status' => $status, 'msg' => $msg, 'payment_url' => $payment_url]);
    }

    public function order_placed(Request $request)
    {
        if ($request->session()->has('ORDER_ID')) {
            return view('front.order_placed');
        } else {
            return redirect('/');
        }
    }

    public function order_fail(Request $request)
    {
        if ($request->session()->has('ORDER_ID')) {
            return view('front.order_fail');
        } else {
            return redirect('/');
        }
    }

    public function instamojo_payment_redirect(Request $request)
    {
        if ($request->get('payment_id') !== null && $request->get('payment_status') !== null && $request->get('payment_request_id') !== null) {
            if ($request->get('payment_status') == 'Credit') {
                $status = 'Success';
                $redirect_url = '/order_placed';
            } else {
                $status = 'Fail';
                $redirect_url = '/order_fail';
            }
            $request->session()->put('ORDER_STATUS', $status);
            DB::table('orders')
                ->where(['txn_id' => $request->get('payment_request_id')])
                ->update(['payment_status' => $status, 'payment_id' => $request->get('payment_id')]);
            return redirect($redirect_url);
        } else {
            die('Something went wrong');
        }
    }

    public function order(Request $request)
    {
        $result['orders'] = DB::table('orders')
            ->select('orders.*', 'orders_status.orders_status')
            ->leftJoin('orders_status', 'orders_status.id', '=', 'orders.order_status')
            ->where(['orders.customers_id' => $request->session()->get('FRONT_USER_ID')])
            ->get();
        return view('front.order', $result);
    }

    public function order_detail(Request $request, $id)
    {
        $result['orders_details'] =
            DB::table('orders_details')
            ->select('orders.*', 'orders_details.price', 'orders_details.qty', 'products.name as pname', 'products_attr.attr_image', 'rams.size', 'colors.color', 'orders_status.orders_status')
            ->leftJoin('orders', 'orders.id', '=', 'orders_details.orders_id')
            ->leftJoin('products_attr', 'products_attr.id', '=', 'orders_details.products_attr_id')
            ->leftJoin('products', 'products.id', '=', 'products_attr.products_id')
            ->leftJoin('rams', 'rams.id', '=', 'products_attr.size_id')
            ->leftJoin('orders_status', 'orders_status.id', '=', 'orders.order_status')
            ->leftJoin('colors', 'colors.id', '=', 'products_attr.color_id')
            ->where(['orders.id' => $id])
            ->where(['orders.customers_id' => $request->session()->get('FRONT_USER_ID')])
            ->get();
        if (!isset($result['orders_details'][0])) {
            return redirect('/');
        }
        return view('front.order_detail', $result);
    }

    public function product_review_process(Request $request)
    {
        if ($request->session()->has('FRONT_USER_LOGIN')) {
            $uid = $request->session()->get('FRONT_USER_ID');

            $arr = [
                "rating" => $request->rating,
                "review" => $request->review,
                "products_id" => $request->product_id,
                "status" => 1,
                "customer_id" => $uid,
                "added_on" => date('Y-m-d h:i:s')
            ];
            $query = DB::table('product_review')->insert($arr);
            $status = "success";
            $msg = "Thank you for providing your review";
        } else {
            $status = "error";
            $msg = "Please login to submit your review";
        }
        return response()->json(['status' => $status, 'msg' => $msg]);
    }

    public function payViaAjax(Request $request)
    {
        // dd($request->all());
        $requestData = (array) json_decode($request->cart_json);

        # Here you have to receive all the order data to initate the payment.
        # Lets your oder trnsaction informations are saving in a table called "orders"
        # In orders table order uniq identity is "transaction_id","status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

        $post_data = array();
        $post_data['total_amount'] = $requestData['amount']; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $requestData['cus_name'];
        $post_data['cus_email'] = $requestData['cus_email'];
        $post_data['cus_add1'] = 'Customer Address';
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = $requestData['cus_city'];
        $post_data['cus_state'] = $requestData['cus_state'];
        $post_data['cus_postcode'] = $requestData['cus_zip'];
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = $requestData['cus_phone'];
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "MHS Store";
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";


        #Before  going to initiate the payment order status need to update as Pending.
        $update_product = DB::table('orders')
            ->where('txn_id', $post_data['tran_id'])
            ->updateOrInsert([
                'name' => $post_data['cus_name'],
                'email' => $post_data['cus_email'],
                'mobile' => $post_data['cus_phone'],
                'total_amt' => $post_data['total_amount'],
                'payment_status' => 'Pending',
                'address' => $post_data['cus_add1'],
                'txn_id' => $post_data['tran_id'],
                'currency' => $post_data['currency']
            ]);

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'checkout', 'json');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }
    }

    public function success(Request $request)
    {
        echo "Transaction is Successful";

        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $sslc = new SslCommerzNotification();

        #Check order status in order tabel against the transaction id or order id.
        $order_detials = DB::table('orders')
            ->where('txn_id', $tran_id)
            ->select('txn_id', 'payment_status', 'currency', 'total_amt')->first();

        if ($order_detials->payment_status == 'Pending') {
            $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);

            if ($validation) {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel. Here you need to update order status
                in order table as Processing or Complete.
                Here you can also sent sms or email for successfull transaction to customer
                */
                $update_product = DB::table('orders')
                    ->where('txn_id', $tran_id)
                    ->update(['payment_status' => 'Processing']);

                echo "<br >Transaction is successfully Completed";
            }
        } else if ($order_detials->payment_status == 'Processing' || $order_detials->payment_status == 'Complete') {
            /*
             That means through IPN Order status already updated. Now you can just show the customer that transaction is completed. No need to udate database.
             */
            echo "Transaction is successfully Completed";
        } else {
            #That means something wrong happened. You can redirect customer to your product page.
            echo "Invalid Transaction";
        }
    }

    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_detials = DB::table('orders')
            ->where('txn_id', $tran_id)
            ->select('txn_id', 'payment_status', 'currency', 'total_amt')->first();

        if ($order_detials->payment_status == 'Pending') {
            $update_product = DB::table('orders')
                ->where('txn_id', $tran_id)
                ->update(['payment_status' => 'Failed']);
            echo "Transaction is Falied";
        } else if ($order_detials->payment_status == 'Processing' || $order_detials->payment_status == 'Complete') {
            echo "Transaction is already Successful";
        } else {
            echo "Transaction is Invalid";
        }
    }

    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_detials = DB::table('orders')
            ->where('txn_id', $tran_id)
            ->select('txn_id', 'payment_status', 'currency', 'total_amt')->first();

        if ($order_detials->payment_status == 'Pending') {
            $update_product = DB::table('orders')
                ->where('txn_id', $tran_id)
                ->update(['payment_status' => 'Canceled']);
            echo "Transaction is Cancel";
        } else if ($order_detials->payment_status == 'Processing' || $order_detials->payment_status == 'Complete') {
            echo "Transaction is already Successful";
        } else {
            echo "Transaction is Invalid";
        }
    }

    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {

            $tran_id = $request->input('tran_id');

            #Check order status in order tabel against the transaction id or order id.
            $order_details = DB::table('orders')
                ->where('txn_id', $tran_id)
                ->select('txn_id', 'payment_status', 'currency', 'total_amt')->first();

            if ($order_details->payment_status == 'Pending') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($request->all(), $tran_id, $order_details->amount, $order_details->currency);
                if ($validation == TRUE) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $update_product = DB::table('orders')
                        ->where('txn_id', $tran_id)
                        ->update(['payment_status' => 'Processing']);

                    echo "Transaction is successfully Completed";
                }
            } else if ($order_details->payment_status == 'Processing' || $order_details->payment_status == 'Complete') {

                #That means Order status already updated. No need to udate database.

                echo "Transaction is already successfully Completed";
            } else {
                #That means something wrong happened. You can redirect customer to your product page.

                echo "Invalid Transaction";
            }
        } else {
            echo "Invalid Data";
        }
    }
}
