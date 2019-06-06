<!DOCTYPE html>
<html lang="en">
<head>@include('frontend.common.head')</head>
<body class="cb-page terms">
<div class="cb-pagewrap"> @include('frontend.common.header')
    <section id="mian-content">
        <div class="container">
            <div class="cb-grid">
                <div class="cb-block main-content">
                    <div class="cb-content">
                        <div class="inner-block">
                            <h1 class="heading">
                                <span class="txt1">Terms</span>                  <!--<span class="txt2"></span>-->
                            </h1>
                            <p>
                                <?php $termsData = isset($term_data->content)? breakWords($term_data->content, 642): '' ?>
                                @foreach($termsData as $term)
                                    {!! ($term) !!}
                                @endforeach
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('frontend.common.footer')
</div>
</body>

</html>