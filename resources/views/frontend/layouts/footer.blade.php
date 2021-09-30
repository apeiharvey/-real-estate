
	<!-- Start Footer Area -->
	<footer class="footer">
		<!-- Footer Top -->
		<div class="footer-top section">
			<div class="container">
				<div class="row">
					<div class="col-lg-5 col-md-6 col-12">
						<!-- Single Widget -->
						<div class="single-footer about">
							<div class="logo">
								<a href="index.html"><img src="{{asset('storage/photos/1/logo/10456631_aa2904f3-bd1b-4e8f-ae48-e40991a25e5b.jpg')}}" alt="#"></a>
							</div>
							@php
								$settings=DB::table('settings')->first();
							@endphp
							<p class="call">Got Question? Call us 24/7
								<a href="https://api.whatsapp.com/send?phone={{$settings->phone}}" target="_blank">
									<span>
										+{{$settings->phone}}
									</span>
								</a>
							</p>
							<p class="call">
								<a href="https://api.whatsapp.com/send?phone={{$settings->phone_2}}" target="_blank">
									<span>
										+{{$settings->phone_2}}
									</span>
								</a>
							</p>
							<p class="call">
								<a href="https://api.whatsapp.com/send?phone={{$settings->phone_3}}" target="_blank">
									<span>
										+{{$settings->phone_3}}
									</span>
								</a>
							</p>
							<p class="call">
								<a href="https://api.whatsapp.com/send?phone={{$settings->phone_4}}" target="_blank">
									<span>
										+{{$settings->phone_4}}
									</span>
								</a>
							</p>
						</div>
						<!-- End Single Widget -->
					</div>
					<!-- <div class="col-lg-2 col-md-6 col-12">
						<div class="single-footer links">
							<h4>Information</h4>
							<ul>
								<li><a href="{{route('about-us')}}">About Us</a></li>
								<li><a href="#">Faq</a></li>
								<li><a href="#">Terms & Conditions</a></li>
								<li><a href="{{route('contact')}}">Contact Us</a></li>
								<li><a href="#">Help</a></li>
							</ul>
						</div>
					</div>
					<div class="col-lg-2 col-md-6 col-12">
						<div class="single-footer links">
							<h4>Customer Service</h4>
							<ul>
								<li><a href="#">Payment Methods</a></li>
								<li><a href="#">Money-back</a></li>
								<li><a href="#">Returns</a></li>
								<li><a href="#">Shipping</a></li>
								<li><a href="#">Privacy Policy</a></li>
							</ul>
						</div>
					</div> -->
					<div class="col-lg-3 col-md-6 col-12">
						<!-- Single Widget -->
						<div class="single-footer social">
							<h4 style="margin-bottom:10px">Get In Touch</h4>
							<!-- Single Widget -->
							<div class="contact">
								<ul>
									<li>{{$settings->address}}</li>
									<li>{{$settings->email}}</li>
									<li>+{{$settings->phone}}</li>
									<li>+{{$settings->phone_2}}</li>
									<li>+{{$settings->phone_3}}</li>
									<li>+{{$settings->phone_4}}</li>
								</ul>
							</div>
							<!-- End Single Widget -->
							<div class="contact">
								<ul class="list-main">
									<h4 style="margin-bottom:10px">Meet us in our social media :</h4>
									<p>
										@if(isset($settings->tiktok))
										<a href="{{$settings->tiktok}}" target="_blank">
											<img src="{{asset('backend/img/tik-tok.png')}}">
										</a>
										@endif
										@if(isset($settings->instagram))
										<a href="{{$settings->instagram}}" target="_blank">
											<img src="{{asset('backend/img/instagram.png')}}">
										</a>
										@endif
										@if(isset($settings->facebook))
										<a href="{{$settings->facebook}}" target="_blank">
											<img src="{{asset('backend/img/facebook.png')}}">
										</a>
										@endif
									</p>
								</ul>
							</div>
							<!-- <div class="sharethis-inline-follow-buttons"></div> -->
						</div>
						<!-- End Single Widget -->
					</div>
				</div>
			</div>
		</div>
		<!-- End Footer Top -->
		<div class="copyright">
			<div class="container">
				<div class="inner">
					<div class="row">
						<div class="col-lg-6 col-12">
							<div class="left">
								<p>Copyright © {{date('Y')}} Nadiadressanak  -  All Rights Reserved.</p>
							</div>
						</div>
						<!-- <div class="col-lg-6 col-12">
							<div class="right">
								<img src="{{asset('backend/img/payments.png')}}" alt="#">
							</div>
						</div> -->
					</div>
				</div>
			</div>
		</div>
	</footer>
	<!-- /End Footer Area -->
 
	<!-- Jquery -->
    <script src="{{asset('frontend/js/jquery.min.js')}}"></script>
    <script src="{{asset('frontend/js/jquery-migrate-3.0.0.js')}}"></script>
	<script src="{{asset('frontend/js/jquery-ui.min.js')}}"></script>
	<!-- Popper JS -->
	<script src="{{asset('frontend/js/popper.min.js')}}"></script>
	<!-- Bootstrap JS -->
	<script src="{{asset('frontend/js/bootstrap.min.js')}}"></script>
	<!-- Slicknav JS -->
	<script src="{{asset('frontend/js/slicknav.min.js')}}"></script>
	<!-- Owl Carousel JS -->
	<script src="{{asset('frontend/js/owl-carousel.js')}}"></script>
	<!-- Magnific Popup JS -->
	<script src="{{asset('frontend/js/magnific-popup.js')}}"></script>
	<!-- Waypoints JS -->
	<script src="{{asset('frontend/js/waypoints.min.js')}}"></script>
	<!-- Countdown JS -->
	<script src="{{asset('frontend/js/finalcountdown.min.js')}}"></script>
	<!-- Nice Select JS -->
	<script src="{{asset('frontend/js/nicesellect.js')}}"></script>
	<!-- Flex Slider JS -->
	<script src="{{asset('frontend/js/flex-slider.js')}}"></script>
	<!-- ScrollUp JS -->
	<script src="{{asset('frontend/js/scrollup.js')}}"></script>
	<!-- Onepage Nav JS -->
	<script src="{{asset('frontend/js/onepage-nav.min.js')}}"></script>
	{{-- Isotope --}}
	<script src="{{asset('frontend/js/isotope/isotope.pkgd.min.js')}}"></script>
	<!-- Easing JS -->
	<script src="{{asset('frontend/js/easing.js')}}"></script>

	<!-- Active JS -->
	<script src="{{asset('frontend/js/active.js')}}"></script>

	
	@stack('scripts')
	<script>
		setTimeout(function(){
		  $('.alert').slideUp();
		},5000);
		$(function() {
		// ------------------------------------------------------- //
		// Multi Level dropdowns
		// ------------------------------------------------------ //
			$("ul.dropdown-menu [data-toggle='dropdown']").on("click", function(event) {
				event.preventDefault();
				event.stopPropagation();

				$(this).siblings().toggleClass("show");


				if (!$(this).next().hasClass('show')) {
				$(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
				}
				$(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
				$('.dropdown-submenu .show').removeClass("show");
				});

			});
		});
	  </script>