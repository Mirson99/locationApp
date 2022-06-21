<?php
	require_once('model/db.php');
	require_once('model/user.php');
	$page_help = 'Wybierz opcję zmień Email aby zmienić przypisany podczas rejestracji do konta adres<br>
	Wybierz opcję zmień hasło by zmienić hasło
	';
	$page_title = "Zarządzanie kontem";

	require('view/layout/header.php');
	require('view/layout/top_menu_loggd.php');

	if (!is_logged_in()){
		die("Nie zalogowano !");
	}
	
	
//	echo "E-mail: ".$_SESSION['user_mail'].'<br><br>';
	
?>

				<!-- Zmiana Ad urządzenia -->

				<div class="container">	
					<form method = "POST" action="index.php?page=devices&a=rename&id=<? echo $device_id; ?>">
						<fieldset style="background-color:#F7F7F7">
							<legend style="margin:10px;">Zmiana adresu E-mail przypisanego do konta (<? echo $_SESSION['user_mail']; ?>):</legend>

							<div class="row">
								<div class="col m7">
									<label for="newName">Podaj nowy adres E-mail</label>
									<input class="_full-width" type="text" placeholder="Nazwa urządzenia" id="newName" name="newName" value="<? echo $_SESSION['user_mail']; ?>">
								</div>
<?php
if ($name_error) {
	echo '	
								<div class="col m4" style="height:100%">
									<div class="alert">
									'.$name_error.'
									</div>
								</div>
		';
}

?>
							</div>
						  
							<div class="row" style="margin-left:15px;">
								<input class=" _primary button" type="submit" value="Zmień adres">
								<input class="button" type="reset" value="Reset">
							</div>
						  
						</fieldset>
					</form>
				</div>
				
				
<br>				

				<!-- Zmiana Ad urządzenia -->

				<div class="container">	
					<form method = "POST" action="index.php?page=devices&a=rename&id=<? echo $device_id; ?>">
						<fieldset style="background-color:#F7F7F7">
							<legend style="margin:10px;">Zmień hasło konta urzytkownika: (<? echo $_SESSION['user_mail']; ?>):</legend>

							<div class="row">
								<div class="col m7">
									<label for="newName">Podaj nowe hasło</label>
									<input class="_full-width" type="password" placeholder="hasło" id="newName" name="newName">
								</div>
<?php
if ($name_error) {
	echo '	
								<div class="col m4" style="height:100%">
									<div class="alert">
									'.$name_error.'
									</div>
								</div>
		';
}

?>
							</div>
							

							<div class="row">
								<div class="col m7">
									<label for="newName">Powtórz nowe hasło</label>
									<input class="_full-width" type="password" placeholder="hasło" id="newName" name="newName">
								</div>
<?php
if ($name_error) {
	echo '	
								<div class="col m4" style="height:100%">
									<div class="alert">
									'.$name_error.'
									</div>
								</div>
		';
}

?>
							</div>							
						  
							<div class="row" style="margin-left:15px;">
								<input class=" _primary button" type="submit" value="Zmień hasło">
								<input class="button" type="reset" value="Reset">
							</div>
						  
						</fieldset>
					</form>
				</div>				