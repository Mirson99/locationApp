				<!-- Zmiana nazwy urządzenia -->

				<div class="container">	
					<form method = "POST" action="index.php?page=devices&a=rename&id=<? echo $device_id; ?>">
						<fieldset style="background-color:#F7F7F7">
							<legend style="margin:10px;">Zmiana nazwy urządzenia (<? echo $device_name; ?>):</legend>

							<div class="row">
								<div class="col m7">
									<label for="newName">Podaj nową nazwę urządzenia</label>
									<input class="_full-width" type="text" placeholder="Nazwa urządzenia" id="newName" name="newName" value="<? echo $device_name; ?>">
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
								<input class=" _primary button" type="submit" value="Zmień nazwę">
								<input class="button" type="reset" value="Reset">
								<input class="button" type="button" onclick="window.location='index.php?page=devices';" value="Anuluj">
							</div>
						  
						</fieldset>
					</form>
				</div>