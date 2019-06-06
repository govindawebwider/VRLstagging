<!DOCTYPE html>
<html lang="en">
<head>
    

    @include('frontend.common.head')
  {!! Html::style('css/bs-rating.css') !!}
   <style>
        .reivew-create-page{
                padding: 20px;
                border: 1px solid #fff;
                border-radius: 20px;
                background-color: rgba(0,0,0,.4);
        }
        .caption{
            display: none !important;
        }
    </style>

    

</head>
<body class="cb-page all-artists">	
  <div class="cb-pagewrap">			
    @include('frontend.common.header')		
    <section id="mian-content">			
      <div class="container">				
        <div class="cb-grid">		
            <div class="col-md-12">
                <h1 class="text-center"> Confirm Update Email  </h1>

                <div class="col-md-6">
                    <div class="reivew-create-page">
                    
                        <form name="send-message" method="POST" action="{{route('updateEmailAdress')}}" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="userToken" value="{{$token}}">
                         <div class="form-group">
                            <label><strong> New Email Address | {{substr($user->newEmailRequest,0,4)}}***</strong> </label>
                            <input type="text" name="email" class="form-control" placeholder="confirm email">
                        </div>
                        <input type="submit" class="btn  btn-success" value="update">
                        
                    </form>
                   </div>
                </div>
                
            </div>
          </div>				
        </div>			
      </section>   			
      @include('frontend.common.footer')
      {!! Html::script('js/star-rating.js') !!}
      <script>
        

      </script>

    </div>		
  </body>	
  </html>