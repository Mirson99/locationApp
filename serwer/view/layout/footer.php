			</div>
	</div>

	<!-- Pomoc -->
	<div id="helpModal" class="modalbox-modal">
       <!-- Modal content -->
		<div class="modalbox-modal-content" style="border-radius:5px;padding:10px;">
			<span class="-close" id="modalbox-close2">✖</span>
                    <div style="border:1px dashed pink;border-radius:5px;padding:10px;">
						<? echo $page_help; ?>
					</div>
        </div>
    </div>
	
	<!-- Ciasteczka -->
	<div id="cookieModal" class="modalbox-modal">
       <!-- Modal content -->
		<div class="modalbox-modal-content" style="border-radius:5px;padding:10px;">
					<span class="-close" id="modalbox-close3" style="display:none">✖</span>
                    <div style="border:1px dashed pink;border-radius:5px;padding:10px;">
						<img src="view/images/cookies.png">
						<br />
						Strona wykorzystuje ciasteczka do poprawnego działania (m. in. do przechowywania id sesji)...
						<br />
						<br />
						<center><span class="button" id="modalbox-close3" onclick = "document.getElementById('modalbox-close3').click(); setCookiesNotificationCookie();">Pogódź się z tym</span></center>
					</div>
        </div>
    </div>
		

	<div style="height:90px; background: linear-gradient(white,#070707); ">
		<div id = "footer">
			<hr style="width:100%;">
			<span>Radosław Kozyro</span>
			<span>Przemysław Głód</span>
			<span>Łukasz Mirek</span>
		</div>
	</div>
	<script src = "view/scripts/beauter.min.js"></script>
	<script src = "view/scripts/map_resize.js"></script>
	<script src = "view/scripts/cookieAlert.js"></script>

</body>
</html>