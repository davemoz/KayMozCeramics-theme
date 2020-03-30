( function() {
/*
	const vid = document.getElementById("intro__video");
	
	var audioButton = document.getElementById("audio_button");
	var audioOn = document.querySelector(".audio-on");
	var audioOff = document.querySelector(".audio-off");	
	
	var playingPromise = vid.play();
	if ( playingPromise !== undefined ) {
		playingPromise.then(function(){
			// Autoplay started!
			console.log('Autoplay started!');
		}).catch(error => {
			// Autoplay was prevented.
			console.log('Autoplay was prevented!');
			vid.muted = true;
			vid.play();
			vid.pause();
			vid.play();
			//audioOn.setAttribute("style", "display: inline-block;");
			//audioOff.setAttribute("style", "display: none;");
		});
	}
	*/
	
	
	/*
	vid.oncanplaythrough = function(){
		vid.muted = true;
		vid.play();
		vid.pause();
		vid.play();
	}
	*/
	
	/*
	// Mute button
	audioButton.addEventListener('click', function() {
		if (vid.muted) {
			vid.muted = false;
			audioOn.setAttribute("style", "display: none;");
			audioOff.setAttribute("style", "display: inline-block;");
		} else {
			vid.muted = true;
			audioOff.setAttribute("style", "display: none;");
			audioOn.setAttribute("style", "display: inline-block;");
		}
	});
	
	// Mouse position-triggered section background
	// const el = document.querySelector("#module");
*/
} )();