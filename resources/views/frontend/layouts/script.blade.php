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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCeeHDCOXmUMja1CFg96RbtyKgx381yoBU"></script>
<script>
    var latitude = "{{$setting->lat}}";
    var longitude = "{{$setting->long}}";
    var map_parameters = { center: {lat: parseFloat(latitude), lng: parseFloat(longitude)}, zoom: 3 };
	var map = new google.maps.Map(document.getElementById('map'), map_parameters);

	var position1 = { position: {lat: parseFloat(latitude), lng: parseFloat(longitude)}, map: map };

	var marker1 = new google.maps.Marker(position1);
</script>
<!-- Google Map js -->