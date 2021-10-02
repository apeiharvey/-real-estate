<div class="ltn__slider-area ltn__slider-3  section-bg-2">
    <div class="ltn__slide-one-active banner slick-slide-arrow-1 slick-slide-dots-1">
        @if(isset($banner) && count($banner) > 0)
            @php
                // modulus for text style
                $mod = 3;
                $counter = 1;
            @endphp
            @foreach($banner as $val)
                @if(fmod($counter, $mod) == 0)
                    @php
                    $text_style = 'text-center';
                    @endphp
                @elseif(fmod($counter, $mod) == 1)
                    @php
                    $text_style = 'text-right text-end';
                    @endphp
                @elseif(fmod($counter, $mod) == 2)
                    @php
                    $text_style = 'text-left';
                    @endphp
                @endif
                @php $counter++; @endphp
                <div class="ltn__slide-item ltn__slide-item-2 ltn__slide-item-3-normal--- ltn__slide-item-3 bg-image bg-overlay-theme-black-60" data-bg="{{$val->photo}}">
                    <div class="ltn__slide-item-inner {{$text_style}}">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12 align-self-center">
                                    <div class="slide-item-info">
                                        <div class="slide-item-info-inner ltn__slide-animation">
                                            <h1 class="slide-title animated ">{!!$val->description!!}</h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
        <div class="ltn__slide-item ltn__slide-item-2 ltn__slide-item-3-normal--- ltn__slide-item-3 bg-image bg-overlay-theme-black-60" data-bg="">
            <div class="ltn__slide-item-inner text-center">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 align-self-center">
                            <div class="slide-item-info">
                                <div class="slide-item-info-inner ltn__slide-animation">
                                    <h1 class="slide-title animated "></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>