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
            ctx.fillStyle = "rgb(255,165,0)";
            ctx.fillRect(0, 0, 1, 1);
            
            //gradientMap dataT
            
            var h = 0;
            var v = 47;
            var minT = Math.min.apply(Math, dataT);
            var maxT = Math.max.apply(Math, dataT);
            //echo 'var dataT = [';
            for (var i = 0; i <= 3071; i++) {   //64*48px  = 0..3071 px
                var T = parseFloat(dataT[i]);
                //echo $T.',';
                $colorRGB = self::_temperatureToColor($T, $minT, $maxT);
                imagesetpixel(self::$_imgT, $h, $v, $colorRGB);
                $v--;
                if ($v < 0) {
                    $v = 47;
                    $h++;
                }
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