				<!-- Rejestracja -->

				<center><h2>Rejestracja konta</h2></center>
				<hr>
				<div class="container">	

					<form action="index.php?page=register&a=register" method="POST">
						<fieldset style="background-color:#F7F7F7">

							<div class="row">
								<div class="col m7">
									<label for="Email">Podaj adress Email</label>
									<input class="_full-width" type="email" placeholder="test@mail.com" id="email" name="email" value="<? echo $register_mail; ?>">
								</div>
<?
	if ($mail_error) {
		echo '
								<div class="col m4" style="height:100%">
									<div class="alert">
									'.$mail_error.'
									</div>
								</div>	
			';
	}
?>									
							</div>
							<div class="row">
								<div class="col m7">
									<label for="password">Podaj hasło</label>
									<input class="_full-width" type="password" placeholder="***" id="password" name="password">
								</div>
<?
	if ($password_error) {
		echo '
								<div class="col m4" style="height:100%">
									<div class="alert">
									'.$password_error.'
									</div>
								</div>	
			';
	}
?>										
								
							</div>
							<div class="row">
								<div class="col m7">
									<label for="password_confirm">Powtórz hasło</label>
									<input class="_full-width" type="password" placeholder="***" name="password_confirm" id="password_confirm">
								</div>
<?
	if ($password_confirm_error) {
		echo '
								<div class="col m4" style="height:100%">
									<div class="alert">
									'.$password_confirm_error.'
									</div>
								</div>	
			';
	}
?>							
							</div>						  
							<div class="row" style="margin-left:15px;">
								<input class=" _primary button" type="submit" value="Zarejestruj konto">
								<input class="button" type="reset" value="Reset">
							</div>
						  
						</fieldset>
					</form>
				</div>
