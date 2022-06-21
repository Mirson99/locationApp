<!-- Logowanie -->

				<div class="container">	
				Zaloguj się do systemu, lub <a href="index.php?page=register">Zarejestruj konto.</a><br/><br/>
					<form action="index.php?a=log_in" method="POST">
						<fieldset style="background-color:#F7F7F7">
							<legend style="margin:10px;">Logowanie:</legend>
<?
	if ($login_error) {
		echo '
							<div class="row">
								<div class="col m7" style="height:100%">
									<div class="alert">
									'.$login_error.'
									</div>
								</div>	
							</div>
			';
	}
?>							
							<div class="row">
								<div class="col m7">
									<label for="Email">Podaj adress Email</label>
									<input class="_full-width" type="email" placeholder="test@mail.com" value="<? echo $login_mail; ?>" id="email" name="email">
								</div>
							</div>
							<div class="row">
								<div class="col m7">
									<label for="Email">Podaj hasło</label>
									<input class="_full-width" type="password" placeholder="***" id="password" name="password">
								</div>
							</div>
						  
							<div class="row" style="margin-left:15px;">
								<input class=" _primary button" type="submit" value="Zaloguj się">
								<input class="button" type="reset" value="Reset">
							</div>
						  
						</fieldset>
					</form>
				</div>