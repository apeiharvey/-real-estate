<footer class="ltn__footer-area ">
	<div class="footer-top-area  bg-cendana-sec-1 plr--5">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xl-4 col-md-4 col-sm-12 col-12">
					<div class="footer-widget footer-about-widget">
						<div class="footer-logo">
							<div class="site-logo">
								@if(isset($setting) && !empty($setting->logo))
									<img style="width:150px" src="{{asset($setting->logo)}}" alt="Nama Website">
								@else
								<img src="{{asset('frontend/img/logo-2.png')}}" alt="Nama Website">
								@endif
							</div>
						</div>
						<!-- <p>Nama Website Gallery.</p>
						<div class="footer-address">
							<ul>
								<li>
									<div class="footer-address-icon">
										<i class="icon-placeholder"></i>
									</div>
									<div class="footer-address-info">
										<p>{{(isset($setting) && !empty($setting->address) ? $setting->address : '-')}}</p>
									</div>
								</li>
								<li>
									<div class="footer-address-icon">
										<i class="icon-call"></i>
									</div>
									<div class="footer-address-info">
										@if(isset($setting) && !empty($setting->phone))
										<p><a href="tel:{{$setting->phone}}">{{$setting->phone}}</a></p>
										@else
										<p><a href="#">-</a></p>
										@endif
									</div>
								</li>
							</ul>
						</div> -->
					</div>
					<div class="footer-widget footer-menu-widget clearfix">
						<h3 class="footer-title cendana-text-prim-1">Contact Us</h3>
						<div class="footer-menu cendana-footer">
							<ul>
								<!-- @if(isset($setting) && !empty($setting->email))
								<li>
									<h4>
										<i class="fas fa-envelope mr-2"></i>
										<a target="_blank" href="mailto:{{$setting->email}}">{{$setting->email}}</a>
									</h4>
								</li>
								@endif -->
								<!-- @if(isset($setting) && !empty($setting->twitter))
								<li>
									<h4>
										<i class="fab fa-twitter mr-2"></i>
										<a target="_blank" href="{{$setting->twitter}}">{{$setting->twitter_name}}</a>
									</h4>
								</li>
								@endif -->
								@if(isset($setting) && !empty($setting->facebook))
								<li>
									<h4>
										<i class="fab fa-facebook mr-2"></i>
										<a target="_blank" href="{{$setting->facebook}}">{{$setting->facebook_name}}</a>
									</h4>
								</li>
								@endif
								@if(isset($setting) && !empty($setting->instagram))
								<li>
									<h4>
										<i class="fab fa-instagram mr-2"></i>
										<a target="_blank" href="{{$setting->instagram}}">{{$setting->instagram_name}}</a>
									</h4>
								</li>
								@endif
								@if(isset($setting) && !empty($setting->mobile_phone))
								<li>
									<h4>
										<i class="fab fa-whatsapp mr-2"></i>
										<a target="_blank" href="https://wa.me/{{$setting->mobile_phone}}?text=Saya%20tertarik%20dengan%20rumah%20Anda%20yang%20dijual">{{ wordwrap(str_replace('62','0',$setting->mobile_phone),4,"-",true) }}</a>
									</h4>
								</li>
								@endif
							</ul>
						</div>
					</div>
				</div>
				{{-- <div class="col-xl-4 col-md-4 col-sm-12 col-12">
					<div class="ltn__google-map-locations-area">
						<img src="{{asset($setting->photo)}}">
					</div>
				</div>
				<div class="col-xl-4 col-md-4 col-sm-12 col-12">
					<div class="ltn__google-map-locations-area">
						<img src="{{asset($setting->maps2)}}">
					</div>
				</div> --}}
			</div>
		</div>
	</div>
	<div class="ltn__copyright-area ltn__copyright-2 bg-cendana-sec-1  plr--5">
		<div class="container-fluid ltn__border-top-2">
			<div class="row">
				<div class="col-md-6 col-12">
					<div class="ltn__copyright-design clearfix">
						<p class="cendana-text-prim-1">All Rights Reserved @ Company <span class="current-year"></span></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>