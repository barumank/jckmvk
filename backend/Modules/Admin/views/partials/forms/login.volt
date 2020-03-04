<div class="login_wrapper">
	<div class="animate form login_form">
		<section class="login_content">
			<form method="post" action="{{ form.getAction() }}">
				<h1>Авторизация</h1>
				<div>{{ form.render('login') }}</div>
				{{ partial('forms/error',{'form':form,'fieldName':'login'}) }}

				<div>{{ form.render('password') }}</div>
				{{ partial('forms/error',{'form':form,'fieldName':'password'}) }}
				<div class="checkbox">
					<label for="remember">
						{{ form.render('remember') }} Запомнить вас?
					</label>
				</div>
				<div>
					<button class="btn btn-default">Войти</button>
				</div>
				<div class="clearfix"></div>
				<div class="separator">
					<div class="clearfix"></div>
					<br />
					<div>
						<h1>
							<img src="/admin/images/logo/webempire.png" alt="webempire" width="34" height="34"> WebEmpire
						</h1>
						<p>© 2016-2017 Все права защищены. ООО "Веб империя"</p>
					</div>
				</div>
			</form>
		</section>
	</div>
</div>