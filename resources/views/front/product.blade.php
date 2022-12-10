@extends('front/layout')
@section('page_title',$product[0]->name)
@section('container')


  <!-- catg header banner section -->
  <!--<section id="aa-catg-head-banner">
   <div class="aa-catg-head-banner-area">
     <div class="container">
      <div class="aa-catg-head-banner-content">
        <h2>T-Shirt</h2>
        <ol class="breadcrumb">
          <li><a href="index.html">Home</a></li>         
          <li><a href="#">Product</a></li>
          <li class="active">T-shirt</li>
        </ol>
      </div>
     </div>
   </div>
  </section>-->
  <!-- / catg header banner section -->

  <!-- product category -->
  <section id="aa-product-details">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="aa-product-details-area">
            <div class="aa-product-details-content">
              <div class="row">
                <!-- Modal view slider -->
                <div class="col-md-5 col-sm-5 col-xs-12">                              
                  <div class="aa-product-view-slider">                                
                    <div id="demo-1" class="simpleLens-gallery-container">
                      <div class="simpleLens-container">
                        <div class="simpleLens-big-image-container"><a data-lens-image="{{asset('storage/media/'.$product[0]->image)}}" class="simpleLens-lens-image"><img src="{{asset('storage/media/'.$product[0]->image)}}" class="simpleLens-big-image"></a></div>
                      </div>
                      <div class="simpleLens-thumbnails-container">
                          <a data-big-image="{{asset('storage/media/'.$product[0]->image)}}" data-lens-image="{{asset('storage/media/'.$product[0]->image)}}" class="simpleLens-thumbnail-wrapper" href="#"><img src="{{asset('storage/media/'.$product[0]->image)}}" width="70px">
                          </a>   

                          @if(isset($product_images[$product[0]->id][0]))

                            @foreach($product_images[$product[0]->id] as $list)
                            
                            <a data-big-image="{{asset('storage/media/'.$list->images)}}" data-lens-image="{{asset('storage/media/'.$list->images)}}" class="simpleLens-thumbnail-wrapper" href="#"><img src="{{asset('storage/media/'.$list->images)}}" width="70px">
                            </a>  
                            
                            @endforeach

                          @endif
                                                   
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Modal view content -->
                <div class="col-md-7 col-sm-7 col-xs-12">
                  <div class="aa-product-view-content">
                    <h3>{{$product[0]->name}}</h3>
                    <div class="aa-price-block">
                      <span class="aa-product-view-price">Rs {{$product_attr[$product[0]->id][0]->price}}&nbsp;&nbsp;</span>
                      <span class="aa-product-view-price"><del>Rs {{$product_attr[$product[0]->id][0]->mrp}}</del></span>

                      <p class="aa-product-avilability">Avilability: <span>In stock</span></p>

                       @if($product[0]->lead_time!='')
                       <p class="lead_time">
                       {{$product[0]->lead_time}}
                       </p>
                       @endif 

                    </div>
                    <p>
                    {!!$product[0]->short_desc!!}
                    </p>

                    @if($product_attr[$product[0]->id][0]->size_id>0)
                    <h4>Size</h4>
                    <div class="aa-prod-view-size">
                    @php
                      $arrSize=[];
                      foreach($product_attr[$product[0]->id] as $attr){
                        $arrSize[]=$attr->size;
                      }  
                      $arrSize=array_unique($arrSize);
                     
                    @endphp
                    @foreach($arrSize as $attr)  

                    @if($attr!='')
                      <a href="javascript:void(0)" onclick="showColor('{{$attr}}')" id="size_{{$attr}}" class="size_link">{{$attr}}</a>
                      @endif  

                      @endforeach  
                    </div>
                    @endif
                    
                    
                    @if($product_attr[$product[0]->id][0]->color_id>0)
                    
                    <h4>Color</h4>
                    <div class="aa-color-tag">
                      @foreach($product_attr[$product[0]->id] as $attr)  
                      
                      @if($attr->color!='')

                      <a href="javascript:void(0)" class="aa-color-{{strtolower($attr->color)}} product_color size_{{$attr->size}}"  onclick=change_product_color_image("{{asset('storage/media/'.$attr->attr_image)}}","{{$attr->color}}")></a>
                      @endif  

                      @endforeach  
                    </div>
                    @endif    

                    <div class="aa-prod-quantity">
                      <form action="">
                        <select id="qty" name="qty">
                          @for($i=1;$i<11;$i++)
                            <option value="{{$i}}">{{$i}}</option>
                          @endfor
                        </select>
                      </form>
                      <p class="aa-prod-category">
                      Model: <a href="#">{{$product[0]->model}}</a>
                      </p>
                    </div>
                    <div class="aa-prod-view-bottom">
                      <a class="aa-add-to-cart-btn" href="javascript:void(0)" onclick="add_to_cart('{{$product[0]->id}}','{{$product_attr[$product[0]->id][0]->size_id}}','{{$product_attr[$product[0]->id][0]->color_id}}')">Add To Cart</a>
                    </div>
                    <div id="add_to_cart_msg"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="aa-product-details-bottom">
              <ul class="nav nav-tabs" id="myTab2">
                <li><a href="#description" data-toggle="tab">Description</a></li>
                <li><a href="#technical_specification" data-toggle="tab">Technical Specification</a></li>
                <li><a href="#uses" data-toggle="tab">Uses</a></li>
                <li><a href="#warranty" data-toggle="tab">Warranty</a></li>
                <li><a href="#review" data-toggle="tab">Reviews</a></li>                
              </ul>

              <!-- Tab panes -->
              <div class="tab-content">
                <div class="tab-pane fade in active" id="description">
                  {!!$product[0]->desc!!}
                </div>
                <div class="tab-pane fade" id="technical_specification">
                  {!!$product[0]->technical_specification!!}
                </div>
                <div class="tab-pane fade" id="uses">
                  {!!$product[0]->uses!!}
                </div>
                <div class="tab-pane fade" id="warranty">
                  {!!$product[0]->warranty!!}
                </div>
                <div class="tab-pane fade " id="review">
                 <div class="aa-product-review-area">
                 @if(isset($product_review[0]))    
                   <h4>
                   @php  
                      echo count($product_review);
                   @endphp
                    Review(s) for {{$product[0]->name}}</h4> 
                   <ul class="aa-review-nav">
                     @foreach($product_review as $list)
                     <li>
                        <div class="media">
                          <div class="media-body">
                            <h4 class="media-heading"><strong>{{$list->name}}</strong> - <span>{{getCustomDate($list->added_on)}}</span></h4>
                            <div class="aa-product-rating">
                              <span class="rating_txt">{{$list->rating}}</span>
                            </div>
                            <p>{{$list->review}}</p>
                          </div>
                        </div>
                      </li>
                      @endforeach
                   </ul>
                   @else
                        <h2>No review found</h2>
                      @endif
                   <form id="frmProductReview" class="aa-review-form">
                   <h4>Add a review</h4>
                   <div class="aa-your-rating">
                     <p>Your Rating</p>
                     <select class="form-control" name="rating" required>
                      <option value="">Select Rating</option>
                      <option>Worst</option>
                      <option>Bad</option>
                      <option>Good</option>
                      <option>Very Good</option>
                      <option>Fantastic</option>
                     </select>
                   </div>
                   <!-- review form -->
                   
                      <div class="form-group">
                        <label for="message">Your Review</label>
                        <textarea class="form-control" rows="3"  name="review" required></textarea>
                      </div>
                      
                      <button type="submit" class="btn btn-default aa-review-submit">Submit</button>
                      <input type="hidden" name="product_id" value="{{$product[0]->id}}"/>
                      @csrf
                   </form>
                   <div class="review_msg"></div>
                 </div>
                </div>            
              </div>
            </div>
            <!-- Related product -->
            <div class="aa-product-related-item">
              <h3>Related Products</h3>
              <ul class="aa-product-catg aa-related-item-slider">
               
              @if(isset($related_product[0]))
                    @foreach($related_product as $productArr)
                    <li>
                        <figure>
                        <a class="aa-product-img" href="{{url('product/'.$productArr->slug)}}"><img src="{{asset('storage/media/'.$productArr->image)}}" alt="{{$productArr->name}}"></a>
                        <a class="aa-add-card-btn" href="{{url('product/'.$productArr->slug)}}"><span class="fa fa-shopping-cart"></span>Add To Cart</a>
                        <figcaption>
                            <h4 class="aa-product-title"><a href="{{url('product/'.$productArr->slug)}}">{{$productArr->name}}</a></h4>
                            <span class="aa-product-price">Rs {{$related_product_attr[$productArr->id][0]->price}}</span><span class="aa-product-price"><del>Rs {{$related_product_attr[$productArr->id][0]->mrp}}</del></span>
                        </figcaption>
                        </figure>                          
                    </li>  
                    @endforeach    
                    @else
                    <li>
                        <figure>
                        No data found
                        <figure>
                    <li>
                    @endif      
                
                                 
              </ul>
            </div>  
          </div>
        </div>
      </div>
    </div>
  </section>
  <form id="frmAddToCart">
    <input type="hidden" id="size_id" name="size_id"/>
    <input type="hidden" id="color_id" name="color_id"/>
    <input type="hidden" id="pqty" name="pqty"/>
    <input type="hidden" id="product_id" name="product_id"/>           
    @csrf
  </form>
@endsection