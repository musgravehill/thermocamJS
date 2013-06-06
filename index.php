<html>
    <head>
        <title>TC</title>
        <script src='/gradientMap.js'></script>
    </head>
    <body>
        <canvas height='320' width='480' id='thermalCanvas'>Обновите браузер</canvas>
        <script>
            var thermalCanvas = document.getElementById("thermalCanvas");
            var ctx = thermalCanvas.getContext('2d');
            ctx.fillStyle = "rgb(255,165,0)";
            ctx.fillRect(0, 0, 1, 1);
            
            //gradientMap
            
            
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