@push('styles')
<style>
.modal-body {
   padding: 0;
}
</style>
@endpush
<div class="bg-cendana-neu-1 ltn__apartments-plan-area pt-115" id="unit-type">
    <div class="container">
        <div class="row">
            @if($unit_type)
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2--- text-center">
                    <h1 class="section-title cendana-text-sec-1">Unit Type</h1>
                </div>
                @php
                $counter = 0;
                @endphp
                @foreach($unit_type as $row)
                <div class="row">
                    @if($counter%2 == 1)
                    <div class="col-lg-6 align-self-center">
                        <div class="about-us-img-wrap about-img-right">
                            <img src="{{asset($row->images_thumbnail)}}" alt="About Us Image">
                        </div>
                    </div>
                    <div class="col-lg-6 align-self-center">
                        <div class="about-us-info-wrap">
                            <div class="section-title-area ltn__section-title-2--- mb-30">
                                <h1 class="section-title">{{$row->name}}</h1>
                                <p>hiya hiya</p>
                                <a href="#" title="Quick View" data-toggle="modal" data-target="#quick_view_modal_{{$counter}}">
                                    READ MORE
                                </a>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="col-lg-6 align-self-center">
                        <div class="about-us-info-wrap">
                            <div class="section-title-area ltn__section-title-2--- mb-30">
                                <h1 class="section-title">{{$row->name}}</h1>
                                <p>hiya hiya</p>
                                <a href="#" title="Quick View" data-toggle="modal" data-target="#quick_view_modal_{{$counter}}">
                                    READ MORE
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 align-self-center">
                        <div class="about-us-img-wrap about-img-right">
                            <img src="{{asset($row->images_thumbnail)}}" alt="About Us Image">
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal fade" id="quick_view_modal_{{$counter}}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-body">
                            <!-- carousel -->
                                <div
                                    id='carouselExampleIndicators'
                                    class='carousel slide'
                                    data-ride='carousel'
                                    >
                                    @php
                                        $counter++;
                                        $data=explode(',',$row->images_detail);
                                    @endphp
                                    <ol class='carousel-indicators'>
                                        @for($i=0; $i<count($data); $i++)
                                            <li data-target='#carouselExampleIndicators' data-slide-to='{{$i}}' class='active'></li>
                                        @endfor
                                    </ol>
                                    <div class='carousel-inner'>
                                        @for($j=0; $j<count($data); $j++)
                                            <div class='carousel-item active'>
                                                <img class='img-size' src="{{asset($data[$j])}}" alt='First slide' />
                                            </div>
                                        @endfor
                                    </div>
                                    <a class='carousel-control-prev' href='#carouselExampleIndicators' role='button' data-slide='prev'>
                                        <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                                        <span class='sr-only'>Previous</span>
                                    </a>
                                    <a class='carousel-control-next' href='#carouselExampleIndicators' role='button' data-slide='next'>
                                        <span class='carousel-control-next-icon' aria-hidden='true'></span>
                                        <span class='sr-only'>Next</span>
                                    </a>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="col-lg-6 align-self-center">
                <div class="about-us-info-wrap">
                    <div class="section-title-area ltn__section-title-2--- mb-30">
                        <h1 class="section-title">Today Sells Properties</h1>
                        <p>hiya hiya</p>
                        <a href="#" class="modal_read" data-toggle="modal" data-target="#largeModal" style="color: #007bff">READ MORE</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 align-self-center">
                <div class="about-us-img-wrap about-img-right">
                    <img src="images/others/9.png" alt="About Us Image">
                </div>
            </div>
            @endif
        </div>
    </div>
</div>