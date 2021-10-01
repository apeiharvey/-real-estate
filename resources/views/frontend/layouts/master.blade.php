<!DOCTYPE html>
<html class="no-js" lang="zxx">
<head>
	@include('frontend.layouts.head')	
</head>
<body>
	<div class="body-wrapper">
		@include('frontend.layouts.header')
		@include('frontend.layouts.mobile_menu')
		<div class="ltn__utilize-overlay"></div>
		@yield('main-content')
		@include('frontend.layouts.footer')
		@include('frontend._includes.modal_user_mortage')
	</div>
	@include('frontend.layouts.script')
</body>
</html>