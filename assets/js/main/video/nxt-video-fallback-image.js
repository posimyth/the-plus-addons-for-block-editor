function initVideoFallback() {
	document.querySelectorAll( ".ts-video-wrapper" ).forEach(
		function (wrapper) {
			var video = wrapper.querySelector( "video" );
			var img   = wrapper.querySelector( ".nxt-fallback-img" );

			if ( ! video || ! img) {
				return;
			}

			var sources    = video.querySelectorAll( "source" );
			var errorCount = 0;
			var loaded     = false;

			function showFallback() {
				video.style.display = "none";
				img.style.display   = "block";
			}

			function showVideo() {
				video.style.display = "block";
				img.style.display   = "none";
				loaded              = true;
			}

			// No sources or unsupported
			if ( ! sources.length || ! video.canPlayType) {
				showFallback();
				return;
			}

			// Check format support
			var supported = false;
			sources.forEach(
				function (src) {
					var type = src.getAttribute( "type" );
					if (type && video.canPlayType( type )) {
						supported = true;
					}

					src.addEventListener(
						"error",
						function () {
							if (++errorCount >= sources.length) {
								showFallback();
							}
						}
					);
				}
			);

			if ( ! supported) {
				showFallback();
				return;
			}

			// Video events
			video.addEventListener(
				"canplay",
				function () {
					if ( ! loaded) {
						showVideo();
					}
				}
			);

			video.addEventListener( "error", showFallback );

			// Timeout fallback
			setTimeout(
				function () {
					if ( ! loaded && video.readyState === 0) {
						showFallback();
					}
				},
				8000
			);
		}
	);
}

// Initialize
if (document.readyState === "loading") {
	document.addEventListener( "DOMContentLoaded", initVideoFallback );
} else {
	initVideoFallback();
}
