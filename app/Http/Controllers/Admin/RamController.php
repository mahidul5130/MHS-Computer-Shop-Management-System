<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Admin\Ram;
use Illuminate\Http\Request;

class RamController extends Controller
{
    public function index()
    {
        $result['data']=Ram::all();
        return view('admin/ram',$result);
    }

    
    public function manage_ram(Request $request,$id='')
    {
        if($id>0){
            $arr=Ram::where(['id'=>$id])->get(); 

            $result['size']=$arr['0']->size;
            $result['status']=$arr['0']->status;
            $result['id']=$arr['0']->id;
        }else{
            $result['size']='';
            $result['status']='';
            $result['id']=0;
            
        }
        return view('admin/manage_ram',$result);
    }

    public function manage_ram_process(Request $request)
    {
        //return $request->post();
        
        $request->validate([
            'size'=>'required|unique:rams,size,'.$request->post('id'),   
        ]);

        if($request->post('id')>0){
            $model=Ram::find($request->post('id'));
            $msg="Ram updated";
        }else{
            $model=new Ram();
            $msg="Ram inserted";
        }
        $model->size=$request->post('size');
        $model->status=1;
        $model->save();
        $request->session()->flash('message',$msg);
        return redirect('admin/ram');
        
    }

    public function delete(Request $request,$id){
        $model=Ram::find($id);
        $model->delete();
        $request->session()->flash('message','Ram deleted');
        return redirect('admin/ram');
    }

    public function status(Request $request,$status,$id){
        $model=Ram::find($id);
        $model->status=$status;
        $model->save();
        $request->session()->flash('message','Ram status updated');
        return redirect('admin/ram');
    }
}
