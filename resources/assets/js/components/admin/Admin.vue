<template>
	<div class="Admin">
		<aside class="aside">
			<nav class="sidebar">
				<ul class="nav">
					<li class="nav-heading">Main navigation</li>
					<li>
						<a href="home">
							<em class="fa fa-dot-circle-o" aria-hidden="true"></em> Dashboard
						</a>
					</li>
					<li><a href="#">Link002</a></li>
					<li><a href="#">Link003</a></li>
					<li><a href="#">Link004</a></li>
					<li><a href="#">Link005</a></li>
				</ul>
			</nav>
		</aside>
		
		<section>
			<div class="content-wrapper">
				<!-- @yield('content') -->
			</div>
			<!-- END Page content-->
		</section>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				
			}
		},
		mounted() {
			console.log('ready');
		}
	}
</script>