<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;

class BrandController extends Controller
{
    public function index()
    {
        $result['data']=Brand::all();
        return view('admin/brand',$result);
    }

    
    public function manage_brand(Request $request,$id='')
    {
        if($id>0){
            $arr=Brand::where(['id'=>$id])->get(); 

            $result['name']=$arr['0']->name;
            $result['image']=$arr['0']->image;
            $result['is_home']=$arr['0']->is_home;
            $result['is_home_selected']="";
            if($arr['0']->is_home==1){
                $result['is_home_selected']="checked";
            }
            $result['status']=$arr['0']->status;
            $result['id']=$arr['0']->id;
        }else{
            $result['name']='';
            $result['image']='';
            $result['is_home']="";
            $result['is_home_selected']="";
            $result['status']='';
            $result['id']=0;
            
        }
        return view('admin/manage_brand',$result);
    }

    public function manage_brand_process(Request $request)
    {
        //return $request->post();
        
        $request->validate([
            'name'=>'required|unique:brands,name,'.$request->post('id'), 
            'image'=>'mimes:jpeg,jpg,png'
        ]);

        if($request->post('id')>0){
            $model=Brand::find($request->post('id'));
            $msg="Brand updated";
        }else{
            $model=new Brand();
            $msg="Brand inserted";
        }

        if($request->hasfile('image')){

            if($request->post('id')>0){
                $arrImage=DB::table('brands')->where(['id'=>$request->post('id')])->get();
                if(Storage::exists('/media/brand/'.$arrImage[0]->image, ['disk' => 'public_upload'])){
                    Storage::delete('/media/brand/'.$arrImage[0]->image, ['disk' => 'public_upload']);
                }
            }

            $image=$request->file('image');
            $ext=$image->extension();
            $image_name=time().'.'.$ext;
            $image->storeAs('/media/brand',$image_name, ['disk' => 'public_upload']);
            $model->image=$image_name;
        }
        $model->is_home=0;
        if($request->post('is_home')!==null){
            $model->is_home=1;
        }
        $model->name=$request->post('name');
        $model->status=1;
        $model->save();
        $request->session()->flash('message',$msg);
        return redirect('admin/brand');
        
    }

    public function delete(Request $request,$id){
        $model=brand::find($id);
        $model->delete();
        $request->session()->flash('message','Brand deleted');
        return redirect('admin/brand');
    }

    public function status(Request $request,$status,$id){
        $model=Brand::find($id);
        $model->status=$status;
        $model->save();
        $request->session()->flash('message','Brand status updated');
        return redirect('admin/brand');
    }
}
