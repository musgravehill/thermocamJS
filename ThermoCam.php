<?php

$ThermoCam = new ThermoCam('gradients/gradient-bry.png');
$ThermoCam->getImgT('data/tempvalues-1.txt',400);

class ThermoCam {

    private static $_gradientColorMap;
    private static $_x = 64;
    private static $_y = 48;
    private static $_imgT = null;

    public function __construct($gradientImgFile) {
        self::$_gradientColorMap = self::_createGradientColorMap($gradientImgFile);
        self::$_imgT = imagecreatetruecolor(self::$_x, self::$_y);
    }

    public function getImgT($dataFile,$widthImgT) {
        $file = file($dataFile);
        unset($file[3072]);
        unset($file[3073]);
        unset($file[3074]);
        unset($file[3075]);
        unset($file[3076]);
        $h = 0;
        $v = 47;
        $minT = min($file);
        $maxT = max($file);
        for ($i = 0; $i <= 3071; $i++) {   //64*48px  = 0..3071 px
            $T = (float) $file[$i];
            $colorRGB = self::_temperatureToColor($T, $minT, $maxT);
            imagesetpixel(self::$_imgT, $h, $v, $colorRGB);
            $v--;
            if ($v < 0) {
                $v = 47;
                $h++;
            }
        }

        header('Content-Type: image/png');
        imagefilter(self::$_imgT, IMG_FILTER_SMOOTH, 50);
        $image = new SimpleImage();
        $image->loadObj(self::$_imgT);
        $image->resizeToWidth((int)$widthImgT);
        $image->output();
    }

    private static function _temperatureToColor($T, $minT, $maxT) {
        $oneStep = ($maxT - $minT) / sizeof(self::$_gradientColorMap);
        $pos = round(($T - $minT) / $oneStep) - 1;  // 0 ...1000
        if ($pos < 0) {
            $pos = 0;
        }
        return self::$_gradientColorMap[$pos];
    }

    private static function _createGradientColorMap($gradientImgFile) {
        $gradientColorMap = array();
        $gradientImg = imagecreatefrompng($gradientImgFile);

        $image = new SimpleImage();
        $image->loadObj($gradientImg);
        $gradientImgWidth = $image->getWidth(); //1000

        for ($px_horizontal = 0; $px_horizontal < $gradientImgWidth; $px_horizontal++) {
            $rgb = imagecolorat($gradientImg, $px_horizontal, 0);
            $gradientColorMap [] = $rgb;
        }
        return $gradientColorMap;
    }

}

class SimpleImage {

    var $image;
    var $image_type;

    function loadObj($imgObj) {
        $this->image_type == IMAGETYPE_PNG;
        $this->image = $imgObj;
    }

    function load($filename) {

        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];
        if ($this->image_type == IMAGETYPE_JPEG) {

            $this->image = imagecreatefromjpeg($filename);
        } elseif ($this->image_type == IMAGETYPE_GIF) {

            $this->image = imagecreatefromgif($filename);
        } elseif ($this->image_type == IMAGETYPE_PNG) {

            $this->image = imagecreatefrompng($filename);
        }
    }

    function save($filename, $image_type = IMAGETYPE_JPEG, $compression = 75, $permissions = null) {

        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image, $filename, $compression);
        } elseif ($image_type == IMAGETYPE_GIF) {

            imagegif($this->image, $filename);
        } elseif ($image_type == IMAGETYPE_PNG) {

            imagepng($this->image, $filename);
        }
        if ($permissions != null) {

            chmod($filename, $permissions);
        }
    }

    function output($image_type = IMAGETYPE_JPEG) {

        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image);
        } elseif ($image_type == IMAGETYPE_GIF) {

            imagegif($this->image);
        } elseif ($image_type == IMAGETYPE_PNG) {

            imagepng($this->image);
        }
    }

    function getWidth() {

        return imagesx($this->image);
    }

    function getHeight() {

        return imagesy($this->image);
    }

    function resizeToHeight($height) {

        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
    }

    function resizeToWidth($width) {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width, $height);
    }

    function scale($scale) {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize($width, $height);
    }

    function resize($width, $height) {
        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }

}

?>