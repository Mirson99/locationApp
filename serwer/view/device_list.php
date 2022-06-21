				<!-- urządzenia -->

				<center><h2>Moje urządzenia</h2></center>
				<hr>
				
				<table class="devices_table">
				  <thead>
					<tr>
					  <th>Nazwa urządzenia</th>
					  <th>Status</th>
					  <th>Akcje</th>
					</tr>
				  </thead>
				  <tbody>
<?php

foreach ($devices as $device) {
echo '
					<tr>
					  <td><a href="index.php?page=map&id='.$device['id'].'">'.$device['name'].'</a></td>
					  <td>'.$device['status'].'</td>
					  <td>
						<div  class="tooltip -tooltip-bottom">
							<a href="index.php?page=devices&a=delete&id='.$device['id'].'">
								<img src="view/images/delete.png"/>
							</a>
							<span class="tooltiptext _primary">Usuń urządzenie</span>
						</div>
						
						<div  class="tooltip -tooltip-bottom" >
							<a href="index.php?page=devices&a=rename&id='.$device['id'].'">
								<img src="view/images/rename.png"/>
							</a>
							<span class="tooltiptext _primary">Zmień nazwę urządzenia</span>
						</div>
						
						<div  class="tooltip -tooltip-bottom" >
							<a href="index.php?page=map&a=show&id='.$device['id'].'">
								<img src="view/images/map.png"/>
							</a>
							<span class="tooltiptext _primary">Pokaż historie lokalizacji</span>
						</div>						
					  </td>
					</tr>

';

}


?>
				  </tbody>
				</table>
