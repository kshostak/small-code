var el = document.getElementById( 'fullscreenBtn' );
  if( el ) {
		var options = document.getElementById( 'options' );
		el.addEventListener( 'click', function( e ) {
			if( document.body.webkitRequestFullScreen ) {
				document.body.onwebkitfullscreenchange = function(e) {
				//	options.style.display = 'none';
					document.body.style.width = window.innerWidth + 'px';
					document.body.style.height = window.innerHeight + 'px';
					document.body.onwebkitfullscreenchange = function() {
				//		options.style.display = 'block';
					};
				};
				document.body.webkitRequestFullScreen();
			}
			if( document.body.mozRequestFullScreen ) {
				/*document.body.onmozfullscreenchange = function( e ) {
					options.style.display = 'none';
					document.body.onmozfullscreenchange = function( e ) {
						options.style.display = 'block';
					};
				};*/
				document.body.mozRequestFullScreen();
			}
			e.preventDefault();
		}, false );
	}
