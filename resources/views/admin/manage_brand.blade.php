@extends('admin/layout')
@section('page_title','Manage Brand')
@section('brand_select','active')
@section('container')


@error('image')
<div class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
   {{$message}}  
   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
         <span aria-hidden="true">×</span>
   </button>
</div> 
@enderror
    <h1 class="mb10">Manage Brand</h1>
    <a href="{{url('admin/brand')}}">
        <button type="button" class="btn btn-success">
            Back
        </button>
    </a>
    <div class="row m-t-30">
        <div class="col-md-12">
        <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{route('brand.manage_brand_process')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <label for="color" class="control-label mb-1">Brand </label>
                                            <input id="brand" value="{{$name}}" name="name" type="text" class="form-control" aria-required="true" aria-invalid="false" required>
                                            @error('name')
                                            <div class="alert alert-danger" role="alert">
                                                {{$message}}		
                                            </div>
                                            @enderror 
                                        </div>
                                        <div class="col-lg-142">
                                            <label for="image" class="control-label mb-1"> Show in Home Page</label>
                                            <input id="is_home" name="is_home" type="checkbox" class="form-control" {{$is_home_selected}}>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="image" class="control-label mb-1"> Image</label>
                                    <input id="image" name="image" type="file" class="form-control" aria-required="true" aria-invalid="false">
                                    @error('image')
                                    <div class="alert alert-danger" role="alert">
                                    {{$message}}		
                                    </div>
                                    @enderror

                                    @if($image!='')
                                        <img width="100px" src="{{asset('storage/media/brand/'.$image)}}"/>
                                    @endif
                                </div>
                                <div>
                                    <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">
                                        Submit
                                    </button>
                                </div>
                                <input type="hidden" name="id" value="{{$id}}"/>
                            </form>
                        </div>
                    </div>
                </div>
                
            </div>
                        
        </div>
    </div>
                        
@endsection