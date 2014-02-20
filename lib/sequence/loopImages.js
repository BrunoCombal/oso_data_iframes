function rightNow() {
    if (window['performance'] && window['performance']['now']) {
	return window['performance']['now']();
    } else {
	return +(new Date());
    }
}

var fps  = 2;
var currentFrame = 0;
var totalFrames  = 96;
var img          = document.getElementById("loopImageDIV");
var currentTime  = rightNow();

(function animloop(time){
    var delta = (time - currentTime) / 1000;

    currentFrame += Math.abs(delta * fps);
 
    var frameNum = Math.floor(currentFrame)+1;
    console.log(frameNum);

    if (frameNum >= totalFrames) {
	currentFrame = 0; 
	frameNum = 0;
    }

    requestAnimationFrame(animloop);
  
    img.src = "/private_store/cmip5_slr/animation/color_total_slr_" + ( frameNum < 10 ? "0" : "") + frameNum + ".png";
  
    currentTime = time;

    var thisTextDIV=document.getElementById("loopTextDIV");
    thisTextDIV.innerHTML=2005+frameNum;

})(currentTime);
