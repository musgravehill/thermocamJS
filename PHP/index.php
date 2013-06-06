<!DOCTYPE HTML>
<html>
    <head>
        <title>TC</title>        
    </head>
    <body>        
        <h2>ThermoCam->getImgT</h2>
        <img src ="/thermalImg.php?&width=64&data=tempvalues.txt" />
        <img src ="/thermalImg.php?&width=64&data=tempvalues-1.txt" />
        <h2>ThermoCam::getJSGradientMap</h2>   
        <?php 		
        include_once 'ThermoCam.php';
        echo ThermoCam::getJSGradientMap('gradients/gradient-bry.png');
        ?>
    </body>
</html>