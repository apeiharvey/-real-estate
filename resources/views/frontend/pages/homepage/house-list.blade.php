<div class="ltn__about-us-area pb-90 " id="unit-type">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2--- text-center">
                    <div class="btn-wrapper">
                        <a href="{{config('app.app_asset_url').'/'.$setting->brochure}}" class="btn btn-effect-3 btn-orange text-white" download>Get Brochure</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-title-area ltn__section-title-2--- text-center">
            <h1 class="section-title">Unit Type</h1>
        </div>
        <div class="row">
            @if($unit_type)
                @php $counter=0; @endphp
                @foreach($unit_type as $val)
                    @if($counter%2 == 0)
                    <div class="col-lg-6 align-self-center">
                        <div class="about-us-img-wrap about-img-left">
                            <img src="{{config('app.app_asset_url').'/frontend/img/others/9.png'}}" alt="About Us Image">
                        </div>
                    </div>
                    <div class="col-lg-6 align-self-center">
                        <div class="about-us-info-wrap">
                            <div class="section-title-area ltn__section-title-2---">
                                <h1 class="section-title">{{$val->name}}</h1>
                                <a href="#" title="See More {{$val->name}}" data-bs-toggle="modal" data-bs-target="#quick_view_modal" data-unit="{{$val->id}}">See More..</a>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="col-lg-6 align-self-center">
                        <div class="about-us-info-wrap">
                            <div class="section-title-area ltn__section-title-2---">
                                <h1 class="section-title">{{$val->name}}</h1>
                                <a href="#" title="See More {{$val->name}}" data-bs-toggle="modal" data-bs-target="#quick_view_modal" data-unit="{{$val->id}}">See More..</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 align-self-center">
                        <div class="about-us-img-wrap about-img-left">
                            <img src="{{config('app.app_asset_url').'/frontend/img/others/9.png'}}" alt="About Us Image">
                        </div>
                    </div>
                    @endif
                    @php $counter++ @endphp
                @endforeach
            @endif
        </div>
    </div>
</div>
@push('scripts')
<script>
    $('.modal-slick').slick({
        arrows: false,
        dots: false,
        infinite: true,
        autoplay: true,
        autoplaySpeed: 3000,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
    });
    
    var myModalEl = document.getElementById('quick_view_modal');
    myModalEl.addEventListener('show.bs.modal', function (event) {
        // do something...
        $('.modal-slick').slick('slickRemove');
        html = '';
        unit_id = event.relatedTarget.attributes['data-unit'].value;
        data = {
            '_token': "{{csrf_token()}}",
            'unit': unit_id
        }
        $.ajax({
            type: 'post',
            data: data,
            url: '{{route("ajax.post",["slug" => "get_unit_images"])}}',
            success: function( response ) {
                console.log(response)
                if(response.is_ok == true){
                    $.each(response.data, function(idx, val){
                        html += '<div class="slide-item-img">';
                        html += '<img src="'+val+'"></div>';
                    });
                    $('.modal-slick').slick('slickAdd', html);
                }
            }
        });
        // for(i=0; i<3; i++){
        //     html += '<div class="slide-item-img">'
        //     html += '<img src="'+asset_url+'frontend/img/others/9.png" alt="#"></div>';
        // }
        // $('.modal-slick').slick('slickAdd', html);
    });

</script>
@endpush