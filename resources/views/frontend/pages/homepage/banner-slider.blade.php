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
                        <div class="item youtube-sound">
                            <iframe class="embed-player" style="width:100%; height:450px" src="{{$val->url.'?autoplay=1&enablejsapi=1&mute=1'}}"></iframe>
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
                    dotsClass: "home-banner-dots",
                    fade: true,
                    autoplay: true,
                    focusOnSelect: true,
                    pauseOnDotsHover: true,
                    pauseOnHover: true,
                    pauseOnFocus: true,
                    arrows: true,
                    prevArrow: "#btn-prev-home-banner",
                    nextArrow: "#btn-next-home-banner",
                });

                $homeBannerSlide.on(
                    "beforeChange",
                    function (event, slick, currentSlide, nextSlide) {
                        console.log(event, slick, currentSlide, nextSlide);
                        if ($(`#yt-${nextSlide}`).length == 1) {
                            if (ytPlayers[nextSlide] == null) {
                                let videoId = $(`#yt-${nextSlide}`).attr(
                                    "data-ytid"
                                );

                                player = new YT.Player(`yt-${nextSlide}`, {
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

                        if ($(`#yt-${currentSlide}`).length == 1) {
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
{{-- <script>
    var slideWrapper = $(".banner"),
        iframes = slideWrapper.find('.embed-player'),
        lazyImages = slideWrapper.find('.slide-image'),
        lazyCounter = 0;

    function postMessageToPlayer(player, command){
        if (player == null || command == null) return;
        player.contentWindow.postMessage(JSON.stringify(command), "*");
        console.log(player.contentWindow.postMessage(JSON.stringify(command), "*"))
    }
    function playPauseVideo(slick, control){
        var currentSlide, slideType, startTime, player, video;

        currentSlide = slick.find('div.item');
        slideType = currentSlide.attr("class").split(" ")[1];
        player = currentSlide.find("iframe").get(0);
        startTime = currentSlide.data("video-start");
        if (slideType === "youtube-sound") {
            switch (control) {
            case "play":
                postMessageToPlayer(player, {
                "event": "command",
                "func": "playVideo"
                });
                break;
            case "pause":
                postMessageToPlayer(player, {
                "event": "command",
                "func": "pauseVideo"
                });
                break;
            }
        }  else if (slideType === "youtube") {
            switch (control) {
            case "play":
                postMessageToPlayer(player, {
                "event": "command",
                "func": "mute"
                });
                postMessageToPlayer(player, {
                "event": "command",
                "func": "playVideo"
                });
                break;
            case "pause":
                postMessageToPlayer(player, {
                "event": "command",
                "func": "pauseVideo"
                });
                break;
            }
        }
    }
    // Resize player
    function resizePlayer(iframes, ratio) {
        if (!iframes[0]) return;
        var win = $(".main-slider"),
            width = win.width(),
            playerWidth,
            height = win.height(),
            playerHeight,
            ratio = ratio || 16/9;

        iframes.each(function(){
            var current = $(this);
            if (width / ratio < height) {
            playerWidth = Math.ceil(height * ratio);
            current.width(playerWidth).height(height).css({
                left: (width - playerWidth) / 2,
                top: 0
                });
            } else {
            playerHeight = Math.ceil(width / ratio);
            current.width(width).height(playerHeight).css({
                left: 0,
                top: (height - playerHeight) / 2
            });
            }
        });
    }

    // DOM Ready
    $(function() {
    // Initialize
    slideWrapper.on("init", function(slick){
        slick = $(slick.currentTarget);
        setTimeout(function(){
        playPauseVideo(slick,"play");
        }, 1000);
        resizePlayer(iframes, 16/9);
    });
    slideWrapper.on("beforeChange", function(event, slick) {
        slick = $(slick.$slider);
        playPauseVideo(slick,"pause");
    });
    slideWrapper.on("afterChange", function(event, slick) {
        slick = $(slick.$slider);
        playPauseVideo(slick,"pause");
    });

    //start the slider
    slideWrapper.slick({
        // fade:true,
        autoplaySpeed:4000,
        autoplay: true,
        lazyLoad:"progressive",
        speed:600,
        arrows:false,
        dots:false,
        cssEase:"cubic-bezier(0.87, 0.03, 0.41, 0.9)"
    });
    });

    // Resize event
    $(window).on("resize.slickVideoPlayer", function(){  
    resizePlayer(iframes, 16/9);
    });
</script> --}}
@endpush