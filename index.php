<html>
    <head>
        <title>TC</title>
        <script src='/gradientMap.js'></script>
        <script src='/dataT.js'></script>

    </head>
    <body>
        <canvas height='320' width='480' id='thermalCanvas'>Обновите браузер</canvas>
        <script>
            var thermalCanvas = document.getElementById("thermalCanvas");
            var ctx = thermalCanvas.getContext('2d');
            
            
            //gradientMap dataT
            
            var h = 0;
            var v = 47;
            var minT = Math.min.apply(Math, dataT);
            var maxT = Math.max.apply(Math, dataT); alert(minT+'  '+maxT); 
            //echo 'var dataT = [';
            for (var i = 0; i <= 3071; i++) {   //64*48px  = 0..3071 px
                var T = parseFloat(dataT[i]);               
                
                var colorRGB = temperatureToColor(T, minT, maxT);                
                ctx.fillStyle = "rgb("+colorRGB[0]+","+colorRGB[1]+","+colorRGB[2]+")";
                ctx.fillRect(h, v, 1, 1);
                v--;
                if (v < 0) {
                    v = 47;
                    h++;
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











        <h2>ThermoCam->getImgT</h2>
        <img src ="/thermalImg.php?&width=64&data=tempvalues.txt" />
        <img src ="/thermalImg.php?&width=64&data=tempvalues-1.txt" />


        <h2>ThermoCam::getJSGradientMap</h2>
        <?php
        //include_once 'ThermoCam.php';
        //echo ThermoCam::getJSGradientMap('gradients/gradient-bry.png');
        ?>
    </body>
</html>