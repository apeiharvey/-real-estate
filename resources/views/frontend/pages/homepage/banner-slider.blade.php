<div class="ltn__slider-area ltn__slider-3">
    <div class="ltn__slide-one-active banner slick-slide-arrow-1 slick-slide-dots-1">
        @if(isset($banner) && count($banner) > 0)
            @foreach($banner as $val)
                <div class="slide-item-img">
                    <img style="width:100%" src="{{asset($val->photo)}}"/>
                </div>
            @endforeach
        @endif
    </div>
</div>