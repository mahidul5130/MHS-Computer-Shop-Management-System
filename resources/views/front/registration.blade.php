@extends('front/layout')
@section('page_title','Registration')
@section('container')


 <!-- Cart view section -->
 <section id="aa-myaccount">
   <div class="container">
     <div class="row">
       <div class="col-md-12">
        <div class="aa-myaccount-area">         
            <div class="row">
              
              <div class="col-md-6">
                <div class="aa-myaccount-register">                 
                 <h4>Register</h4>
                 <form action="" class="aa-login-form" id="frmRegistration">
                    <label for="">Name<span>*</span></label>
                    <input type="text" name="name" placeholder="Name" required>
                    <div id="name_error" class="field_error"></div>
                    
                    <label for="">Email<span>*</span></label>
                    <input type="email" name="email" placeholder="Email" required>
                    <div id="email_error" class="field_error"></div>

                    <label for="">Password<span>*</span></label>
                    <input type="password" placeholder="Password" name="password">
                    <div id="password_error" class="field_error"></div> 

                    <label for="">Mobile<span>*</span></label>
                    <input type="text" name="mobile" placeholder="Mobile" required>
                    <div id="mobile_error" class="field_error"></div>

                    <button type="submit" class="aa-browse-btn" id="btnRegistration">Register</button>    
                    @csrf                
                  </form>
                </div>
                <div id="thank_you_msg" class="field_error"></div>
              </div>
            </div>          
         </div>
       </div>
     </div>
   </div>
 </section>
 <!-- / Cart view section -->


@endsection