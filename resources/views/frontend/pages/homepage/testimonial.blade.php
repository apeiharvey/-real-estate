<div class="ltn__testimonial-area ltn__testimonial-4 pt-115 pb-100 plr--9 bg-cendana-neu-1">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2--- text-center">
                    <h1 class="section-title cendana-text-sec-1">Our Testimonial</h1>
                </div>
            </div>
        </div>
        <div class="row">
            @if(isset($testimonies) && count($testimonies) > 0)
            <div class="col-lg-12">
                <div class="ltn__testimonial-slider-4 ltn__testimonial-slider-4-active slick-arrow-1">
                    @foreach($testimonies as $val)
                        <div class="ltn__testimonial-item-5">
                            <div class="ltn__quote-icon">
                                <i class="far fa-comments"></i>
                            </div>
                            <div class="ltn__testimonial-image">
                                <img src="{{asset($val->image)}}" alt="{{$val->name}}">
                            </div>
                            <div class="ltn__testimonial-info">
                                {!!$val->text!!}
                            </div>
                        </div>
                    @endforeach
                </div>
                <ul class="ltn__testimonial-quote-menu d-none d-lg-block">
                    @foreach($testimonies as $val)
                    <li>
                        <img src="{{asset($val->image)}}" alt="{{$val->name}}">
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
        <div class="row mt-5">
            <div class="col-lg-12 text-center">
                <div class="btn-wrapper">
                    <a class="btn btn-cen-prim-1" target="_blank" href="https://wa.me/{{$setting->mobile_phone}}?text=Saya%20tertarik%20dengan%20rumah%20Anda%20yang%20dijual">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ltn__testimonial-area section-bg-1--- bg-image-top pt-115 pb-70" data-bg="img/bg/20.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2--- text-center">
                    <h6 class="section-subtitle section-subtitle-2 ltn__secondary-color">Our Testimonial</h6>
                    <h1 class="section-title">Clients Feedback</h1>
                </div>
            </div>
        </div>
        <div class="row ltn__testimonial-slider-5-active slick-arrow-1">
            <div class="col-lg-12">
                <div class="ltn__testimonial-item ltn__testimonial-item-7">
                    <div class="ltn__testimoni-info">
                        <p><i class="flaticon-left-quote-1"></i> 
                            Precious ipsum dolor sit amet
                            consectetur adipisicing elit, sed dos
                            mod tempor incididunt ut labore et
                            dolore magna aliqua. Ut enim ad min
                            veniam, quis nostrud Precious ips
                            um dolor sit amet, consecte</p>
                        <div class="ltn__testimoni-info-inner">
                            <div class="ltn__testimoni-img">
                                <img src="img/testimonial/1.jpg" alt="#">
                            </div>
                            <div class="ltn__testimoni-name-designation">
                                <h5>Jacob William</h5>
                                <label>Selling Agents</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="ltn__testimonial-item ltn__testimonial-item-7">
                    <div class="ltn__testimoni-info">
                        <p><i class="flaticon-left-quote-1"></i> 
                            Precious ipsum dolor sit amet
                            consectetur adipisicing elit, sed dos
                            mod tempor incididunt ut labore et
                            dolore magna aliqua. Ut enim ad min
                            veniam, quis nostrud Precious ips
                            um dolor sit amet, consecte</p>
                        <div class="ltn__testimoni-info-inner">
                            <div class="ltn__testimoni-img">
                                <img src="img/testimonial/1.jpg" alt="#">
                            </div>
                            <div class="ltn__testimoni-name-designation">
                                <h5>Jacob William</h5>
                                <label>Selling Agents</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="ltn__testimonial-item ltn__testimonial-item-7">
                    <div class="ltn__testimoni-info">
                        <p><i class="flaticon-left-quote-1"></i> 
                            Precious ipsum dolor sit amet
                            consectetur adipisicing elit, sed dos
                            mod tempor incididunt ut labore et
                            dolore magna aliqua. Ut enim ad min
                            veniam, quis nostrud Precious ips
                            um dolor sit amet, consecte</p>
                        <div class="ltn__testimoni-info-inner">
                            <div class="ltn__testimoni-img">
                                <img src="img/testimonial/1.jpg" alt="#">
                            </div>
                            <div class="ltn__testimoni-name-designation">
                                <h5>Jacob William</h5>
                                <label>Selling Agents</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="ltn__testimonial-item ltn__testimonial-item-7">
                    <div class="ltn__testimoni-info">
                        <p><i class="flaticon-left-quote-1"></i> 
                            Precious ipsum dolor sit amet
                            consectetur adipisicing elit, sed dos
                            mod tempor incididunt ut labore et
                            dolore magna aliqua. Ut enim ad min
                            veniam, quis nostrud Precious ips
                            um dolor sit amet, consecte</p>
                        <div class="ltn__testimoni-info-inner">
                            <div class="ltn__testimoni-img">
                                <img src="img/testimonial/1.jpg" alt="#">
                            </div>
                            <div class="ltn__testimoni-name-designation">
                                <h5>Jacob William</h5>
                                <label>Selling Agents</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
        </div>
    </div>
</div>