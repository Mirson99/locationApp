				<!-- Usuwanie urządzenia -->

				<div class="container">	
					<form method = "POST" action="index.php?page=devices&a=delete&id=<? echo $device_id; ?>">
						<fieldset style="background-color:#F7F7F7">
							<legend style="margin:10px;">Usuwanie urządzenia (<? echo $device_name; ?>):</legend>

							<div class="row">
								<div class="col m10" style="height:100%">
									<div class="alert">
									Czy na pewno chcesz usunąć urządzenie? <h4><? echo $device_name; ?></h4> Operacji tej nie da później się cofnąć!
									</div>
								</div>		
							</div>
						  
							<div class="row" style="margin-left:15px;">
								<input class=" _primary button _danger" type="submit" value="Usuń urządzenie">
								<input class="button" type="button" onclick="window.location='index.php?page=devices';" value="Anuluj">
							</div>
						  
						</fieldset>
					</form>
				</div>