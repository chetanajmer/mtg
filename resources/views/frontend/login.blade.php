@if(Session::has('userid'))
<script>

window.location.href = "{{url('/store/myaccount')}}";</script>

</script>
@else
@endif

@extends('layouts.frontapp')

@section('content')



<div id="content">
		<div class="content-page woocommerce">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h2 class="title-shop-page dark font-bold play-font">Member</h2>
						<div class="register-content-box">
							<div class="row">
								<div class="col-md-6 col-sm-6 col-ms-12">
									<div class="check-billing">
										<div class="form-my-account">
										<div class="container">
										@if(Session::has('loginerror'))
											<div class="alert alert-danger" id="msg" role="alert"> {{ Session::get('loginerror') }}</div>
										@endif

										@if(Session::has('loginsuccess'))
											<div class="alert alert-success" id="msg" role="alert"> {{ Session::get('loginsuccess') }}</div>
										@endif

										@if(Session::has('error'))
											<div class="alert alert-danger" id="msg" role="alert"> {{ Session::get('error') }}</div>
											@endif
											@if(Session::has('success'))
											<div class="alert alert-success" id="msg" role="alert"> {{ Session::get('succes') }}</div>
											@endif
										@if(Session::has('logoutsuccess'))
											<div class="alert alert-success" id="msg" role="alert"> {{ Session::get('logoutsuccess') }}</div>
										@endif
										</div>
										<form class="block-login"  action="{{ route('dostorelogin') }}" method="post" class="form-horizontal" enctype="multipart/form-data">
										{{ csrf_field() }}
												<h2 class="title24 title-form-account play-font">Login</h2>
												<p>
													<label>Email address <span class="required">*</span></label>
													<input type="text" name="email" required=""/>
												</p>
												<p>
													<label>Password <span class="required">*</span></label>
													<input type="password" name="password" required=""/>
												</p>
												<p>
													<input type="submit" class="register-button" name="login" value="Login">
												</p>
												<div class="table-custom create-account">
													<!--<div class="text-left">-->
													<!--	<p>-->
													<!--		<input type="checkbox"  id="remember" /> <label for="remember">Remember me</label>-->
													<!--	</p>-->
													<!--</div>-->
													<!--<div class="text-right">-->
													<!--	<a href="#" class="color">Lost your password?</a>-->
													<!--</div>-->
												</div>
												<!--<h2 class="title18 social-login-title">Or login with</h2>-->
												<!--<div class="social-login-block table-custom text-center">-->
												<!--	<div class="social-login-btn">-->
												<!--		<a href="#" class="login-fb-link">Facebook</a>-->
												<!--	</div>-->
												<!--	<div class="social-login-btn">-->
												<!--		<a href="#" class="login-goo-link">Google</a>-->
												<!--	</div>-->
												<!--</div>-->
											</form>
											@if(Session::has('error'))
											<div class="alert alert-danger" id="msg" role="alert"> {{ Session::get('error') }}</div>
											@endif
											@if(Session::has('success'))
											<div class="alert alert-success" id="msg" role="alert"> {{ Session::get('succes') }}</div>
											@endif
											<form class="block-register" action="{{ route('dostoreregister') }}" method="post" class="form-horizontal" enctype="multipart/form-data">
											{{ csrf_field() }}
												<h2 class="title24 title-form-account play-font">REGISTER</h2>
												<p>
													<label>Firstname <span class="required">*</span></label>
													<input type="text" name="firstname" id="firstname" required="" />
												</p>
												<p>
													<label>Lastname <span class="required">*</span></label>
													<input type="text" name="lastname" id="lastname" required="" />
												</p>
												<p>
													<label>Email address <span class="required">*</span></label>
													<input type="text" name="email" id="email" required="" />
												</p>
												<p>
													<label>Password <span class="required">*</span></label>
													<input type="password" name="password" id="password" required=""/>
												</p>
												<p>
													<input type="submit" class="register-button" name="register" value="Register">
												</p>
											</form>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-ms-12">
									<div class="check-address">
										<div class="form-my-account check-register text-center">
											<h2 class="title24 title-form-account play-font">Register</h2>
											<p class="desc">Registering for this site allows you to access your order status and history. Just fill in the fields below, and we’ll get a new account set up for you in no time. We will only ask you for information necessary to make the purchase process faster and easier.</p>
											<a href="#" class="shop-button bg-dark login-to-register" data-login="Login" data-register="Register">Register</a>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Content Page -->
	</div>
	<!-- End Content -->



@endsection