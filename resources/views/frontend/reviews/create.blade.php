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
                <h1 class="text-center"> Review  {{$data->artistName}}  and leave a message for   {{$data->sender_name}}  </h1>

                <div class="col-md-6">
                    <div class="reivew-create-page">
                    
                        <form name="send-message" method="POST" action="{{route('storeReview')}}" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="request_token" value="{{$request_token}}">
                        <input type="hidden" name="reciever_name" value="{{$data->reciever_name}}">
                        <input type="hidden" name="sender_email" value="{{$data->sender_email}}">
                        <input type="hidden" name="sender_name" value="{{$data->sender_name}}">

                        <div class="form-group">
                            <label><strong> Review for  {{$data->artistName}}</strong> </label>
                            <textarea class="form-control" name="artist_review" placeholder="Leave a review For the artist"></textarea>
                           
                        </div>
                        <hr>
                        <div class="form-group">
                            <label><strong> Message to  {{$data->sender_name}} </strong> </label>
                            <textarea class="form-control" name="message_to_sender" placeholder="Leave a review For the sender"></textarea>
                           
                        </div>
                        <hr>
                        <div class="form-group">
                           <label><strong> Rate   Video Request Line  </strong> </label>
                           <input id="input-17a" name="rate" class="rating" 
                                  data-size="md"
                                  value="1"
                                  data-glyphicon="false" 
                                  data-rating-class="fontawesome-icon">

                        </div>
                        <hr>
                        <div class="form-group">
                            <input type="submit" class="btn btn-success" value="Send your Review">
                        </div>
                        
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