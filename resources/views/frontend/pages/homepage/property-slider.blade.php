
<div class="ltn__search-by-place-area section-bg-1 before-bg-top--- bg-image-top--- pt-115 pb-70" data-bs-bg="{{asset('frontend/img/bg/20.jpg')}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2--- text-center">
                    <h1 class="section-title">RISE UP! AND GET A HIGHER QUALITY LIVING</h1>
                </div>
            </div>
        </div>
        @if(isset($rooms))
        <div class="row ltn__search-by-place-slider-1-active property slick-arrow-1" id="uptown-state">
            @if(count($rooms) < 3)
                @php
                    $col = 'col-lg-6';
                @endphp
            @else
            @php
                $col = 'col-lg-4';
            @endphp
            @endif
            @foreach($rooms as $val)
            <div class="{{$col}}">
                <div class="ltn__search-by-place-item">
                    <div class="search-by-place-img">
                        <img src="{{asset($val->images)}}" alt="{{$val->name}}">
                    </div>
                    <div class="search-by-place-info">
                        <h4>{{$val->house_name}}</h4>
                        <h6>{{$val->room_name}}</h6>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
        @if(isset($facilities))
        <div class="row ltn__search-by-place-slider-1-active facilities slick-arrow-1" id="facility">
            @if(count($facilities) < 3)
                @php
                    $col = 'col-lg-6';
                @endphp
            @else
            @php
                $col = 'col-lg-4';
            @endphp
            @endif
            @foreach($facilities as $val)
            <div class="{{$col}}">
                <div class="ltn__search-by-place-item">
                    <div class="search-by-place-img">
                        <img src="{{asset($val->images)}}" alt="{{$val->name}}">
                    </div>
                    <div class="search-by-place-info">
                        <h4>{{$val->house_name}}</h4>
                        <h6>{{$val->room_name}}</h6>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2--- text-center">
                    <div class="btn-wrapper">
                        <a href="{{asset($setting->brochure)}}" class="btn btn-effect-3 btn-orange text-white" download>Get Brochure</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>