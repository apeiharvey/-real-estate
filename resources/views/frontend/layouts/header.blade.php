<!-- HEADER AREA START (header-5) -->
<header class="ltn__header-area ltn__header-5 ltn__header-logo-and-mobile-menu-in-mobile ltn__header-logo-and-mobile-menu ltn__header-transparent bg-cendana-sec-1 text-white pt-0 pb-0">
    <!-- ltn__header-middle-area start -->
    <div class="ltn__header-middle-area ltn__header-sticky bg-cendana-sec-1 text-white pt-0 pb-0">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="site-logo-wrap">
                        <div class="site-logo">
                            <a href="{{route('home')}}">
								@if(isset($setting) && !empty($setting->logo))
                                <img style="width:130px" src="{{asset($setting->logo)}}" alt="Nama Website">
								@else
                                <img src="{{asset('frontend/img/logo-2.png')}}" alt="Nama Website">
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col header-menu-column menu-color-white">
                    <div class="header-menu d-none d-xl-block">
                        <nav>
                            <div class="ltn__main-menu">
                                <ul>
                                    <li><a class="linkss pointer" href="{{route('home')}}#unit-type" data-href="#unit-type">Unit Type</a></li>
                                    <li><a class="linkss pointer" href="{{route('home')}}#facility" data-href="#facility">Promotion</a></li>
                                    <li><a class="linkss pointer" href="{{route('home')}}#contact" data-href="#contact">Contact</a></li>
                                    <li><a class="pointer" style="color:white" title="Mortgage Simulation" data-bs-toggle="modal" data-bs-target="#modal_form">Mortgage Simulation</a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
                <div class="col--- ltn__header-options ltn__header-options-2 ">
                    <!-- Mobile Menu Button -->
                    <div class="mobile-menu-toggle d-xl-none">
                        <a href="#ltn__utilize-mobile-menu" class="ltn__utilize-toggle">
                            <svg viewBox="0 0 800 600">
                                <path d="M300,220 C300,220 520,220 540,220 C740,220 640,540 520,420 C440,340 300,200 300,200" id="top"></path>
                                <path d="M300,320 L540,320" id="middle"></path>
                                <path d="M300,210 C300,210 520,210 540,210 C740,210 640,530 520,410 C440,330 300,190 300,190" id="bottom" transform="translate(480, 320) scale(1, -1) translate(-480, -318) "></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ltn__header-middle-area end -->
</header>
<!-- HEADER AREA END -->