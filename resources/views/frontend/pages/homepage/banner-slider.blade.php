<div class="ltn__slider-area ltn__slider-3">
    <div class="ltn__slide-one-active banner slick-slide-arrow-1 slick-slide-dots-1">
        @if(isset($banner) && count($banner) > 0)
            @foreach($banner as $val)
                <div class="slide-item-img">
                    @if(isset($val->url))
                        @if($val->type == 'image')
                        <a href="{{$val->url}}" target="_blank">
                            <img style="width:100%" src="{{asset($val->photo)}}"  title="{{$val->description}}"/>
                        </a>
                        {{-- <img style="width:100%" src="{{ENV('APP_ASSET_URL').$val->photo}}" title="{{$val->description}}"/> --}}
                        @elseif($val->type == 'video')
                        <div class="video-container item youtube-sound">
                            <iframe class="embed-player" style="width:100%; height: 100%" src="{{$val->url.'?enablejsapi=1'}}" title="{{$val->description}}" frameborder="0" allow="autoplay;" sandbox="allow-scripts allow-same-origin allow-presentation"></iframe>
                        </div>
                        @endif
                    @else
                        <img style="width:100%" src="{{asset($val->photo)}}"/>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</div>