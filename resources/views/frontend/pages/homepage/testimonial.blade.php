<div class="ltn__testimonial-area ltn__testimonial-4 pt-115 pb-100 plr--9">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2--- text-center">
                    <h1 class="section-title">Our Testimonial</h1>
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
    </div>
</div>