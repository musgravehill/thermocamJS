/**
 * @author musgravehill
 */
var dataT = [];   //array of float_temperatures (make from file) 0..3071  
var canvasT = [];  //array[0..64][0..47] use on canvas mouseMove\Click
for (var i=0;i<64;i++) {
    canvasT[i] = [];
}
var scaleV = 10;//image scale from 48 to 480px
var scaleH = 10;//image scale from 64 to 640px
var minT = 0; //minimal Temperature in array
var maxT = 0; //max Temperature in array
var arrayTemperatureMaxPos = 64*48-1; //0..3071
    
document.getElementById('fileinput').addEventListener('change', readDataTemperatureFile, false);  
function readDataTemperatureFile(evt) {                
    var f = evt.target.files[0]; 
    if (f) {
        var fr = new FileReader();
        fr.onload = function(e) {               
            makeArrayTemperatureByFile(e); 
            minT = Math.min.apply(Math, dataT); 
            maxT = Math.max.apply(Math, dataT);
            drawThermalImage();
            initializeSliderMinMaxT();
        }
        fr.readAsText(f);
    } else { 
        alert("Failed to load file");
    }
}  
function makeArrayTemperatureByFile(e){      
    dataT = e.target.result.split('\n');    
    dataT.splice((arrayTemperatureMaxPos+1), 20);//unset 25,thermal,r64,l48 and other rotten      
}                      
function drawThermalImage(){    
    var thermalCanvas = document.getElementById("thermalCanvas");
    var ctx = thermalCanvas.getContext('2d');   
    ctx.clearRect(0, 0, thermalCanvas.width, thermalCanvas.height);
    var h = 0;
    var v = 47;       
    for (var i = 0; i <= arrayTemperatureMaxPos; i++) {   //64*48px  = 0..3071 px
        var T = parseFloat(dataT[i]);                 
        var colorRGB = temperatureToColor(T);                
        ctx.fillStyle = "rgb("+colorRGB[0]+","+colorRGB[1]+","+colorRGB[2]+")";
        ctx.fillRect(h*scaleH, v*scaleV, scaleH, scaleV);
        canvasT[h][v] = T;
        v--;
        if (v < 0) {
            v = 47;
            h++;
        }
    }      
    $("#minT").html(minT);
    $("#maxT").html(maxT);
}              
function temperatureToColor(T){
    var oneStep = (maxT - minT) / gradientMap.length;
    var pos = Math.round((T - minT) / oneStep) - 1;  // 0 ...1000
    if (pos < 0) {
        pos = 0;
    }
    if(pos > (gradientMap.length-1)){
        pos = gradientMap.length -1;
    }
    return gradientMap[pos];
}
function saveThermalCanvasAsImage(){
    var thermalCanvas=document.getElementById("thermalCanvas");
    window.open(thermalCanvas.toDataURL('image/png'));
}
function pinTemperatureByPixelClick(canvas, message,x,y) {
    var context = canvas.getContext('2d');    
    context.shadowOffsetX = 0;
    context.shadowOffsetY = 0;
    context.shadowBlur = 3;
    context.shadowColor="black";
    context.font = '14pt Arial';    
    context.fillStyle = 'white';
    context.fillText(message, x,y);   
}
function getMousePos(canvas, evt) {
    var rect = canvas.getBoundingClientRect();
    return {
        x: evt.clientX - rect.left,
        y: evt.clientY - rect.top
    };
}
function initializeSliderMinMaxT() {
    var deltaLeftRight = Math.round((maxT-minT)/2);//make slider more wide
    $( "#slider-range-MinMaxT" ).slider({
        range: true,
        min: minT-deltaLeftRight,
        max: maxT+deltaLeftRight,
        values: [ minT, maxT ],
        slide: function( event, ui ) {            
            minT = ui.values[ 0 ];
            maxT = ui.values[ 1 ];            
            drawThermalImage();
        }
    });    
};

var thermalCanvas=document.getElementById("thermalCanvas");
thermalCanvas.addEventListener('click', function(evt) {
    var mousePos = getMousePos(thermalCanvas, evt);
    mousePos.x -=5; //cause I draw 10*10px square. Cursor in center of square, but I need top-left pixel to get T
    mousePos.y -=5;
    var message = '. '+canvasT[Math.round(mousePos.x/scaleH)][Math.round(mousePos.y/scaleV)];    
    pinTemperatureByPixelClick(thermalCanvas, message,mousePos.x, mousePos.y);
}, false);

thermalCanvas.addEventListener('mousemove', function(evt) {
    var mousePos = getMousePos(thermalCanvas, evt);
    mousePos.x -=5; //cause I draw 10*10px square. Cursor in center of square, but I need top-left pixel to get T
    mousePos.y -=5;
    var message = canvasT[Math.round(mousePos.x/scaleH)][Math.round(mousePos.y/scaleV)];    
    $("#temperatureUnderCursor").html(message);
}, false);