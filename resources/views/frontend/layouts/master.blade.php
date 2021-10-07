<!DOCTYPE html>
<meta charset="utf-8">
<html class="no-js" lang="zxx">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="robots" content="noindex, follow" />
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
		@include('frontend._includes.modal_simulate_mortage')
		@include('frontend._includes.modal_user_mortage')
	</div>
	@include('frontend.layouts.script')
</body>
</html>