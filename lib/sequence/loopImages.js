function rightNow() {
    if (window['performance'] && window['performance']['now']) {
	return window['performance']['now']();
    } else {
	return +(new Date());
    }
}

var fps  = 5,
currentFrame = 0,
totalFrames  = 60,
img          = document.getElementById("loopImageDIV"),
currentTime  = rightNow();

(function animloop(time){
    var delta = (time - currentTime) / 1000;

    currentFrame += (delta * fps);

    var frameNum = Math.floor(currentFrame)+1;

    if (frameNum >= totalFrames) {
	currentFrame = frameNum = 0;
    }

    requestAnimationFrame(animloop);
  
    img.src = "/private_store/cmip5_slr/animation/colored_total_slr_" + ( frameNum < 10 ? "0" : "") + frameNum + ".png";
  
    currentTime = time;

    var thisTextDIV=document.getElementById("loopTextDIV");
    thisTextDIV.innerHTML=2006+frameNum;

})(currentTime);
