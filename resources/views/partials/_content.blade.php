<section style="{{ (! $user->check_if_admin_or_manager()) ? 'margin-left: 0 !important; padding: 0 7em;' : 'margin-left: 250px !important;' }}">
	<div class="content-wrapper">
		<h3>
			<div class="pull-right text-center">
				{{-- <div class="text-sm mb-sm">500 ratings</div> --}}
				<div data-bar-color="#cfdbe2" data-height="18" data-bar-width="3" data-bar-spacing="2" data-values="[2,3,4,7,5,7,8,9,5,7,8,4,7]" class="inlinesparkline"><canvas width="63" height="18" style="display: inline-block; width: 63px; height: 18px; vertical-align: top; max-width: 100%;"></canvas></div>
			</div>Dashboard
			<small>
				Hi, {{ $user->access_name() }}. Welcome back!
			</small>
		</h3>

		@yield('content')
	</div>
</section>
