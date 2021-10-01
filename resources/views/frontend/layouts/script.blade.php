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
<script src="{{asset('frontend/js/plugins.js')}}"></script>
<!-- Main JS -->
<script src="{{asset('frontend/js/main.js')}}"></script>

@stack('scripts')

<script>
	let map;

	function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: -34.397, lng: 150.644 },
            zoom: 8,
        });
	}
    $(".linkss").click(function() {
        href = $(this).data('href');
        $('html, body').animate({
            scrollTop: $(href).offset().top
        }, 2000);
    });
</script>
<!-- Google Map js -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCeeHDCOXmUMja1CFg96RbtyKgx381yoBU" async></script>