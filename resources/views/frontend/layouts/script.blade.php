<!-- preloader area start -->
<div class="preloader d-none" id="preloader">
    <div class="preloader-inner">
        <div class="spinner">
            <div class="dot1"></div>
            <div class="dot2"></div>
        </div>
    </div>
</div>
<!-- preloader area end -->

<!-- All JS Plugins -->
<script type="text/javascript" src="https://www.youtube.com/player_api"></script>
<script src="{{asset('frontend/js/plugins.js')}}"></script>
<!-- Main JS -->
<script src="{{asset('frontend/js/main.js')}}"></script>
<script>
    $(".linkss").click(function() {
        href = $(this).data('href');
        $('html, body').animate({
            scrollTop: $(href).offset().top-125
        }, 2000);
        return false;
    });
</script>
@stack('scripts')