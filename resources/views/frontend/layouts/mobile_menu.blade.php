<!-- Utilize Mobile Menu Start -->
<div id="ltn__utilize-mobile-menu" class="ltn__utilize ltn__utilize-mobile-menu bg-cendana-sec-1">
    <div class="ltn__utilize-menu-inner ltn__scrollbar">
        <div class="ltn__utilize-menu-head">
            <div class="site-logo">
                <a href="{{route('home')}}"><img style="width:130px" src="{{asset($setting->logo)}}" alt="Nama Website"></a>
            </div>
            <button class="ltn__utilize-close">×</button>
        </div>
        <div class="ltn__utilize-menu">
            <ul>
                <li><a class="linkss pointer text-white" href="{{route('home')}}#unit-type" data-href="#unit-type">Property</a></li>
                <li><a class="linkss pointer text-white" href="{{route('home')}}#facility" data-href="#facility">Promotion</a></li>
                <li><a class="linkss pointer text-white" href="{{route('home')}}#contact" data-href="#contact">Contact</a></li>
                <li><a class="text-white" title="Mortgage Simulation" data-bs-toggle="modal" data-bs-target="#modal_form">Mortgage Simulation</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- Utilize Mobile Menu End -->