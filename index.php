<!DOCTYPE HTML>
<html>
    <head>
        <title>TC</title>
        <script src='/jquery1.8.min.js'></script>
        <script src='/gradientMap.js'></script>
    </head>
    <body>
        <canvas height='480' width='640' id='thermalCanvas'>Обновите браузер</canvas>
        <input type="file" id="fileinput" />

        <script type="text/javascript">
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
  
            function makeArrayT(fr){
                var markup, n;
                markup = [];     
                dataT = [];
                for (n = 0; n < fr.result.length; ++n) {                     
                    if (fr.result[n] == '\n'){
                        //var tmp = dataT.length;
                        dataT.push(markup.join(""));
                        markup = [];    
                    }
                    else{
                        markup.push(fr.result[n]);
                    }                                           
                }
            }
            
        </script>



        <script>            
            function drawThermalImage(){
                var scaleV = 10;
                var scaleH = 10;
                var thermalCanvas = document.getElementById("thermalCanvas");
                var ctx = thermalCanvas.getContext('2d');           
                var h = 0;
                var v = 47;
                var minT = Math.min.apply(Math, dataT);
                var maxT = Math.max.apply(Math, dataT);
                //echo 'var dataT = [';
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
            
        </script>

        <!--h2>ThermoCam->getImgT</h2>
        <img src ="/thermalImg.php?&width=64&data=tempvalues.txt" />
        <img src ="/thermalImg.php?&width=64&data=tempvalues-1.txt" />
        <h2>ThermoCam::getJSGradientMap</h2>        
        //include_once 'ThermoCam.php';
        //echo ThermoCam::getJSGradientMap('gradients/gradient-bry.png');-->

    </body>
</html>