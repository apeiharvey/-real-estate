<div class="ltn__testimonial-area section-bg-1--- bg-image-top pt-115 pb-70 bg-cendana-neu-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2--- text-center">
                    <h1 class="section-title cendana-text-sec-1">Our Testimonial</h1>
                </div>
            </div>
        </div>
        <div class="row ltn__testimonial-slider-5-active slick-arrow-1">
            @foreach($testimonies as $val)
            <div class="col-lg-12">
                <div class="ltn__testimonial-item ltn__testimonial-item-7">
                    <div class="ltn__testimoni-info">
                        <p class="cendana-text-sec-1 text-center">{!!$val->text!!}</p>
                        <div class="ltn__testimoni-info-inner">
                            <div class="ltn__testimoni-img">
                                <img src="{{asset($val->image)}}" alt="{{$val->name}}">
                            </div>
                            <div class="ltn__testimoni-name-designation">
                                <h5 class="cendana-text-sec-1">{{$val->testimony_name}}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!--  -->
        </div>
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="btn-wrapper">
                    <a class="btn btn-cen-prim-1" target="_blank" href="https://wa.me/{{$setting->mobile_phone}}?text=Saya%20tertarik%20dengan%20rumah%20Anda%20yang%20dijual">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</div>