        function autoResizeDiv()
        {
            document.getElementById('main').style.minHeight = (window.innerHeight-176) +'px';
			mapElement = document.getElementById('map');
			if (mapElement != null) {
				var h = window.innerHeight - 176;
				if (h < 300) h = 300;
				
				mapElement.style.minHeight = h +'px';
				setTimeout(function(){ map.invalidateSize()}, 200);
			}
        }
        window.onresize = autoResizeDiv;
        autoResizeDiv();