<div class="container" style="margin-top: 30px;">
    <div class="row">
        <div class="col-lg-12">
            <div class="section-title-area ltn__section-title-2--- text-center">
                <h1 class="section-title">SCALE UP YOUR PROFIT WITH TRIPLE BENEFIT</h1>
            </div>
        </div>
    </div>
</div>
<div class="ltn__img-slider-area">
    <div class="container-fluid">
        <div class="row ltn__image-slider-4-active slick-arrow-1 slick-arrow-1-inner ltn__no-gutter-all">
            @if(isset($gallery) && count($gallery) > 0)
            @foreach($gallery as $val)
            <div class="col-12">
                <div class="ltn__img-slide-item-4">
                    <a href="#" data-rel="lightcase:myCollection">
                        <img src="{{config('app.app_asset_url').$val->photo}}" alt="{{$val->title}}">
                    </a>
                    @if(isset($val->title))
                    <div class="ltn__img-slide-info">
                        <div class="ltn__img-slide-info-brief">
                            <h6>{{$val->title}}</h6>
                            <h1>{{isset($val->description) ? $val->description : ''}}</h1>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
            @else
            @for($i=0; $i<5; $i++)
            <div class="col-12">
                <div class="ltn__img-slide-item-4">
                    <a href="#">
                        <img src="{{asset('frontend/img/img-slide/21.jpg')}}" alt="Image">
                    </a>
                    <div class="ltn__img-slide-info">
                        <div class="ltn__img-slide-info-brief">
                            <h6>Heart of NYC</h6>
                            <h1><a href="portfolio-details.html">Manhattan </a></h1>
                        </div>
                    </div>
                </div>
            </div>
            @endfor
            @endif
        </div>
    </div>
</div>