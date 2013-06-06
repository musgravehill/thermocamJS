var dataT = [];   //array of float_temperatures    
    
function readSingleFile(evt) {                
    var f = evt.target.files[0]; 
    if (f) {
        var fr = new FileReader();
        fr.onload = function(e) {                         
            makeArrayT(fr);  
            drawThermalImage();
        }
        fr.readAsText(f);
    } else { 
        alert("Failed to load file");
    }
}

document.getElementById('fileinput').addEventListener('change', readSingleFile, false);  
  
function makeArrayT(fr){ //read file like array of char  
    dataT = [];
    var oneT = '';     
    var frLength = fr.result.length;
    for (var n = 0; n < frLength; ++n) {                     
        if (fr.result[n] == '\n'){            
            dataT.push(oneT);
            oneT = '';    
        }
        else{
            oneT += fr.result[n];
        }                                           
    }
}
            
                  
function drawThermalImage(){
    var scaleV = 10;
    var scaleH = 10;
    var thermalCanvas = document.getElementById("thermalCanvas");
    var ctx = thermalCanvas.getContext('2d');   
    ctx.clearRect(0, 0, thermalCanvas.width, thermalCanvas.height);
    var h = 0;
    var v = 47;
    var minT = Math.min.apply(Math, dataT);
    var maxT = Math.max.apply(Math, dataT);    
    for (var i = 0; i <= 3071; i++) {   //64*48px  = 0..3071 px
        var T = parseFloat(dataT[i]);                 
        var colorRGB = temperatureToColor(T, minT, maxT);                
        ctx.fillStyle = "rgb("+colorRGB[0]+","+colorRGB[1]+","+colorRGB[2]+")";
        ctx.fillRect(h*scaleH, v*scaleV, scaleH, scaleV);
        v--;
        if (v < 0) {
            v = 47;
            h++;
        }
    }    
}
            
            
function temperatureToColor(T, minT, maxT){
    var oneStep = (maxT - minT) / gradientMap.length;
    var pos = Math.round((T - minT) / oneStep) - 1;  // 0 ...1000
    if (pos < 0) {
        pos = 0;
    }
    return gradientMap[pos];
}


function saveThermalCanvasAsImage(){
    var thermalCanvas=document.getElementById("thermalCanvas");
    window.open(thermalCanvas.toDataURL('image/png'));
}