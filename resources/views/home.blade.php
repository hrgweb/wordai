@extends('layouts.app')

@section('content')
	<div class="wrapper">
		<!-- START aside-->
		<aside class="aside">
			<!-- START Sidebar (left)-->
			<nav class="sidebar">
				<ul class="nav">
					<li class="nav-heading">Main navigation</li>
					<li>
						<a href="#">
							<em class="fa fa-area-chart" aria-hidden="true"></em> Link001
						</a>
					</li>
					<li><a href="#">Link002</a></li>
					<li><a href="#">Link003</a></li>
					<li><a href="#">Link004</a></li>
					<li><a href="#">Link005</a></li>
				</ul>
			</nav>
			<!-- END Sidebar (left)-->
		</aside>
		<!-- End aside-->
		
		<!-- START Main section-->
		<section>
			<!-- START Page content-->
			<div class="content-wrapper">
				<h3>
					<div class="pull-right text-center">
						{{-- <div class="text-sm mb-sm">500 ratings</div> --}}
						<div data-bar-color="#cfdbe2" data-height="18" data-bar-width="3" data-bar-spacing="2" data-values="[2,3,4,7,5,7,8,9,5,7,8,4,7]" class="inlinesparkline"><canvas width="63" height="18" style="display: inline-block; width: 63px; height: 18px; vertical-align: top; max-width: 100%;"></canvas></div>
					</div>Dashboard
					<small>Hi, {{ $user->name }}. Welcome back!</small>
				</h3>

				<div class="row">
					<word-api user="{{ $user }}"></word-api>
				</div>
			</div>
			<!-- END Page content-->
		</section>
		<!-- END Main section-->
	</div>

@endsection
