<div class="ltn__apartments-plan-area pt-115" id="unit-type">
    <div class="container">
        <div class="row">
            @if($unit_type)
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2--- text-center">
                    <h1 class="section-title">Unit Type</h1>
                </div>
                <div class="ltn__tab-menu ltn__tab-menu-3 ltn__tab-menu-top-right-- text-uppercase--- text-center">
                    <div class="nav">
                        @php $counter=0; $show='';@endphp
                        @foreach($unit_type as $val)
                            @if($counter == 0)
                                @php $show = 'active show'; $counter++; @endphp
                            @else
                            @php $show = ''; @endphp
                            @endif
                            <a class="{{$show}}" data-bs-toggle="tab" href="#{{Str::slug($val->name)}}">{{$val->name}}</a>
                        @endforeach
                    </div>
                </div>
                <div class="tab-content">
                    @php $counter=0; $show=''; @endphp
                    @foreach($unit_type as $val)
                        @if($counter == 0)
                            @php $show = 'active show'; $counter++; @endphp
                        @else
                        @php $show = ''; @endphp
                        @endif
                        <div class="tab-pane fade {{$show}}" id="{{Str::slug($val->name)}}">
                            <div class="ltn__apartments-tab-content-inner">
                                <div class="row">
                                    <div class="col-lg-6 my-auto">
                                        <div class="apartments-plan-info ltn__secondary-bg text-color-white">
                                            <h2>{{$val->name}}</h2>
                                            <div class="apartments-info-list apartments-info-list-color mt-40">
                                                <ul>
                                                    <li><label>Land Area</label><span>{{$val->area_surface}} m<sup>2</sup></span></li>
                                                    <li><label>Building Area</label><span>{{$val->area_building}} m<sup>2</sup></span></li>
                                                    <li><label>Floor</label> <span>{{$val->floor}} Floor</span></li>
                                                    <li><label>Bathroom</label> <span>{{$val->bathroom}} Bathroom</span></li>
                                                    <li><label>Bedroom</label> <span>{{$val->bedroom}} Bedroom</span></li>
                                                </ul>
                                            </div>
                                            <br/>
                                            @if(isset($val->description))
                                            {!!$val->description!!}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 my-auto">
                                        <div class="row house-list slick-arrow-1">
                                            <div class="apartments-plan-img">
                                                <img src="{{asset($val->images_thumbnail)}}" alt="{{$val->name}}">>
                                                @endif
                                            </div>
                                            @if(isset($val->images_detail))
                                                @if(strpos($val->images_detail,','))
                                                    @php
                                                    $images_detail = explode(',', $val->images_detail);
                                                    @endphp
                                                @else
                                                    @php
                                                    $images_detail = array();
                                                    array_push($images_detail, $val->images_detail);
                                                    @endphp
                                                @endif
                                            @endif
                                            @if(isset($images_detail))
                                                @foreach($images_detail as $val_detail)
                                                    <div class="apartments-plan-img">
                                                        <img src="{{asset($val_detail)}}" alt="{{$val->name}}">
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>