<div class="ltn__slider-area ltn__slider-3">
    <div class="ltn__slide-one-active banner slick-slide-arrow-1 slick-slide-dots-1">
        @if(isset($banner) && count($banner) > 0)
            @php $counter=0; @endphp
            @foreach($banner as $val)
                <div class="slide-item-img">
                    @if(isset($val->url))
                        @if($val->type == 'image')
                        <a href="{{$val->url}}" target="_blank">
                            <img style="width:100%" src="{{asset($val->photo)}}"  title="{{$val->description}}"/>
                        </a>
                        {{-- <img style="width:100%" src="{{ENV('APP_ASSET_URL').$val->photo}}" title="{{$val->description}}"/> --}}
                        @elseif($val->type == 'video')
                        <div class="item youtube-sound">
                            <div class="embed-player" id="vid-{{$counter}}" data-vid="{{$val->url}}" style="width:100%; height:450px"></div>
                        </div>
                        @endif
                    @else
                        <img style="width:100%" src="{{asset($val->photo)}}"/>
                    @endif
                </div>
                @php $counter++; @endphp
            @endforeach
        @endif
    </div>
</div>
@push('scripts')
<script>
    function onYouTubeIframeAPIReady() {
        let section = {
            onPlayerReady: (event) => {
                event.target.playVideo();
                event.target.mute();
            },
            init: () => {
                let ytPlayers = [];
                let $homeBannerSlide = $(".banner");

                $homeBannerSlide.slick({
                    lazyLoad: 'ondemand',
                    autoplaySpeed: 5000,
                    slidesToScroll: 1,
                    dots: false,
                    fade: true,
                    autoplay: true,
                    focusOnSelect: true,
                    pauseOnDotsHover: true,
                    pauseOnHover: true,
                    pauseOnFocus: true,
                    arrows: true,
                });

                $homeBannerSlide.on(
                    "beforeChange",
                    function (event, slick, currentSlide, nextSlide) {
                        if ($(`#vid-${nextSlide}`).length == 1) {
                            if (ytPlayers[nextSlide] == null) {
                                let videoId = $(`#vid-${nextSlide}`).attr(
                                    "data-vid"
                                );

                                player = new YT.Player(`vid-${nextSlide}`, {
                                    videoId,
                                    width: "100%",
                                    height: "100%",
                                    playerVars: {
                                        loop: 1,
                                        rel: 0,
                                    },
                                    events: {
                                        onReady: section.onPlayerReady,
                                    },
                                });

                                ytPlayers[nextSlide] = player;
                            } else {
                                let state = ytPlayers[nextSlide].getPlayerState();

                                if (state == 2) {
                                    ytPlayers[nextSlide].playVideo();
                                }
                            }
                        }

                        if ($(`#vid-${currentSlide}`).length == 1) {
                            let state = ytPlayers[currentSlide].getPlayerState();

                            if (state == 1) {
                                ytPlayers[currentSlide].pauseVideo();
                            }
                        }
                    }
                );
            },
        };

        section.init();
    }
</script>
@endpush