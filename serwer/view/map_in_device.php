				<!-- Mapa lokalizacji -->
				<div style="text-align:center">
					<input type="button" value="Ostatnia doba" onclick = "sh_dni(this, 1)">
					<input type="button" value="Ostatnie 3 dni" onclick = "sh_dni(this, 3)">
					<input type="button" value="Ostatni tydzień" onclick = "sh_dni(this, 7)">
					<input type="button" value="Wybrany okres" class="accordion" onclick="change_selection(this);">
					<div class="-panel">
						Od:&nbsp;<input id="dateStart" type="date">&nbsp;<input id="timeStart" type="time"><br/>
						Do:&nbsp;<input id="dateEnd" type="date">&nbsp;<input id="timeEnd" type="time"></br>
						<input id="acc_btn" type="button" value="Pokaż" onclick="sh_okres(this);">

					</div>
				</div>
				<div id="map">
				</div>
				<script language="javascript" src="../view/scripts/map_init.js">
				</script>
				<script  language="javascript" src="../view/scripts/map_functions.js">
				</script>				