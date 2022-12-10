<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Admin\HomeBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;
class HomeBannerController extends Controller
{
    public function index()
    {
        $result['data']=HomeBanner::all();
        return view('admin/home_banner',$result);
    }

    
    public function manage_home_banner(Request $request,$id='')
    {
        if($id>0){
            $arr=HomeBanner::where(['id'=>$id])->get(); 
            $result['image']=$arr['0']->image;
            $result['btn_txt']=$arr['0']->btn_txt;
            $result['btn_link']=$arr['0']->btn_link;
            $result['id']=$arr['0']->id;
        }else{
            $result['image']='';
            $result['btn_txt']='';
            $result['btn_link']='';
            $result['id']="";
        }

        return view('admin/manage_home_banner',$result);
    }

    public function manage_home_banner_process(Request $request)
    {
        $request->validate([
            'image'=>'required|mimes:jpeg,jpg,png' 
        ]);

        if($request->post('id')>0){
            $model=HomeBanner::find($request->post('id'));
            $msg="Banner updated";
        }else{
            $model=new HomeBanner();
            $msg="Banner inserted";
        }

        if($request->hasfile('image')){

            if($request->post('id')>0){
                $arrImage=DB::table('home_banners')->where(['id'=>$request->post('id')])->get();
                if(Storage::exists('/public/media/banner/'.$arrImage[0]->image)){
                    Storage::delete('/public/media/banner/'.$arrImage[0]->image);
                }
            }

            $image=$request->file('image');
            $ext=$image->extension();
            $image_name=time().'.'.$ext;
            $image->storeAs('/public/media/banner',$image_name);
            $model->image=$image_name;

        }
        $model->btn_txt=$request->post('btn_txt');
        $model->btn_link=$request->post('btn_link');
        $model->status=1;
        $model->save();
        $request->session()->flash('message',$msg);
        return redirect('admin/home_banner');
        
    }

    public function delete(Request $request,$id){
        $model=HomeBanner::find($id);
        $model->delete();
        $request->session()->flash('message','Banner deleted');
        return redirect('admin/home_banner');
    }

    public function status(Request $request,$status,$id){
        $model=HomeBanner::find($id);
        $model->status=$status;
        $model->save();
        $request->session()->flash('message','Banner status updated');
        return redirect('admin/home_banner');
    }
    
}
