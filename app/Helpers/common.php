<?php
use Illuminate\Support\Facades\DB;

function prx($arr){
    echo "<pre>";
    print_r($arr);
    die();
}

function getTopNavCat(){
    $result=DB::table('categories')
            ->where(['status'=>1])
            ->get();
            $arr=[];
    foreach($result as $row){
        $arr[$row->id]['category_name']=$row->category_name;
        $arr[$row->id]['parent_id']=$row->parent_category_id;
		$arr[$row->id]['category_slug']=$row->category_slug;
    }
    $str=buildTreeView($arr,0);
    return $str;
}

$html='';
function buildTreeView($arr,$parent,$level=0,$prelevel= -1){
	global $html;
	foreach($arr as $id=>$data){
		if($parent==$data['parent_id']){
			if($level>$prelevel){
				if($html==''){
					$html.='<ul class="nav navbar-nav">';
				}else{
					$html.='<ul class="dropdown-menu">';
				}
				
			}
			if($level==$prelevel){
				$html.='</li>';
			}
			$url=url("/category/".$data['category_slug']);
			$html.='<li><a href="'.$url.'">'.$data['category_name'].'<span class="caret"></span></a>';
			if($level>$prelevel){
				$prelevel=$level;
			}
			$level++;
			buildTreeView($arr,$id,$level,$prelevel);
			$level--;
		}
	}
	if($level==$prelevel){
		$html.='</li></ul>';
	}
	return $html;
}

function getUserTempId(){
	if(!session()->has('USER_TEMP_ID')){
		$rand=rand(111111111,999999999);
		session()->put('USER_TEMP_ID',$rand);
		return $rand;
	}else{
		return session()->get('USER_TEMP_ID');
	}
}

function getAddToCartTotalItem(){
	if(session()->has('FRONT_USER_LOGIN')){
		$uid=session()->get('FRONT_USER_ID');
		$user_type="Reg";
	}else{
		$uid=getUserTempId();
		$user_type="Not-Reg";
	}
	$result=DB::table('cart')
            ->leftJoin('products','products.id','=','cart.product_id')
            ->leftJoin('products_attr','products_attr.id','=','cart.product_attr_id')
            ->leftJoin('sizes','sizes.id','=','products_attr.size_id')
            ->leftJoin('colors','colors.id','=','products_attr.color_id')
            ->where(['user_id'=>$uid])
            ->where(['user_type'=>$user_type])
            ->select('cart.qty','products.name','products.image','sizes.size','colors.color','products_attr.price','products.slug','products.id as pid','products_attr.id as attr_id')
            ->get();

	return $result;
   
}


function apply_coupon_code($coupon_code){
	$totalPrice=0;
	$result=DB::table('coupons')  
		->where(['code'=>$coupon_code])
		->get(); 
	
	if(isset($result[0])){
		$value=$result[0]->value;
		$type=$result[0]->type;
		$getAddToCartTotalItem=getAddToCartTotalItem();
		
		foreach($getAddToCartTotalItem as $list){
			$totalPrice=$totalPrice+($list->qty*$list->price);
		}  
		if($result[0]->status==1){
			if($result[0]->is_one_time==1){
				$status="error";
				$msg="Coupon code already used";    
			}else{
				$min_order_amt=$result[0]->min_order_amt;
				if($min_order_amt>0){
						
					if($min_order_amt<$totalPrice){
						$status="success";
						$msg="Coupon code applied";
					}else{
						$status="error";
						$msg="Cart amount must be greater then $min_order_amt";
					}
				}else{
						$status="success";
						$msg="Coupon code applied";
				}
			}
		}else{
			$status="error";
			$msg="Coupon code deactivated";   
		}
		
	}else{
		$status="error";
		$msg="Please enter valid coupon code";
	}
	
	$coupon_code_value=0;
	if($status=='success'){
		if($type=='Value'){
			$coupon_code_value=$value;
			$totalPrice=$totalPrice-$value;
		}if($type=='Per'){
			$newPrice=($value/100)*$totalPrice;
			$totalPrice=round($totalPrice-$newPrice);
			$coupon_code_value=$newPrice;
		}
	}

	return json_encode(['status'=>$status,'msg'=>$msg,'totalPrice'=>$totalPrice,'coupon_code_value'=>$coupon_code_value]);
}

function getCustomDate($date){
	if($date!=''){
		$date=strtotime($date);
		return date('d-M Y',$date);
	}
}

function getAvaliableQty($product_id,$attr_id){
	$result=DB::table('orders_details')
            ->leftJoin('orders','orders.id','=','orders_details.orders_id')
			->leftJoin('products_attr','products_attr.id','=','orders_details.products_attr_id')
            ->where(['orders_details.product_id'=>$product_id])
            ->where(['orders_details.products_attr_id'=>$attr_id])
            ->select('orders_details.qty','products_attr.qty as pqty')
            ->get();

	return $result;
}
?>