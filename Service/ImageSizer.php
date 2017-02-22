<?php

namespace ShadeSoft\ImageBundle\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ImageSizer {

    /**
     * Set an image to the given width while preserving its ratio
     *
     * @param $img
     * @param $width
     * @param null|"jpeg"|"jpg"|"png"|"gif"|"wbmp"|"bmp" $outputFormat - if null, it won't change
     */
    public function widen($img, $width, $outputFormat = null) {
        list($ow, $oh) = $imgInfo = getimagesize($img);
        $srcImg = $this->getImage($img, $imgInfo);

        if($width == $ow) {
            return;
        }

        // calculate new size
        $nw = $width;
        $nh = round(($nw / $ow) * $oh);

        $this->saveImage($img, $nw, $nh, $ow, $oh, $srcImg, $imgInfo, $outputFormat);
    }

    /**
     * Set an image to the given height while preserving its ratio
     *
     * @param $img
     * @param $height
     * @param null|"jpeg"|"jpg"|"png"|"gif"|"wbmp"|"bmp" $outputFormat - if null, it won't change
     */
    public function heighten($img, $height, $outputFormat = null) {
        list($ow, $oh) = $imgInfo = getimagesize($img);
        $srcImg = $this->getImage($img, $imgInfo);

        if($height == $oh) {
            return;
        }

        // calculate new size
        $nh = $height;
        $nw = round(($nh / $oh) * $ow);

        $this->saveImage($img, $nw, $nh, $ow, $oh, $srcImg, $imgInfo, $outputFormat);
    }

    /**
     * Maximize image's size by its longest dimension while preserving its ratio
     *
     * @param $img
     * @param $maxWidth
     * @param $maxHeight
     * @param null|"jpeg"|"jpg"|"png"|"gif"|"wbmp"|"bmp" $outputFormat - if null, it won't change
     */
    public function maximize($img, $maxWidth, $maxHeight, $outputFormat = null) {
        list($ow, $oh) = $imgInfo = getimagesize($img);
        $srcImg = $this->getImage($img, $imgInfo);

        // calculate new size
        if(($ow > $maxWidth && $oh > $maxHeight && $ow >= $oh) || $ow > $maxWidth) {
            $nw = $maxWidth;
            $nh = round(($nw / $ow) * $oh);
        } elseif(($ow > $maxWidth && $oh > $maxHeight && $ow < $oh) || $oh > $maxHeight) {
            $nh = $maxHeight;
            $nw = round(($nh / $oh) * $ow);
        } else {
            return;
        }

        $this->saveImage($img, $nw, $nh, $ow, $oh, $srcImg, $imgInfo, $outputFormat);
    }

    private function saveImage($img, $nw, $nh, $ow, $oh, $srcImg, $imgInfo, $outputFormat) {
        $dstImg = imagecreatetruecolor($nw, $nh);
        if($imgInfo['mime'] == 'image/png' || $imgInfo['mime'] == 'image/gif') {
            $this->setTransparency($dstImg, $srcImg);
        }
        imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $nw, $nh, $ow, $oh);
        if(!$outputFormat) {
            switch($imgInfo['mime']) {
                case 'image/png':
                    @imagepng($dstImg, $img, 9);
                    break;
                case 'image/gif':
                    @imagegif($dstImg, $img);
                    break;
                case 'image/wbmp':
                    @imagewbmp($dstImg, $img);
                    break;
                case 'image/jpeg':
                default:
                    @imagejpeg($dstImg, $img, 90);
            }
        } else {
            switch($outputFormat) {
                case 'png':
                    @imagepng($dstImg, $img, 9);
                    break;
                case 'gif':
                    @imagegif($dstImg, $img);
                    break;
                case 'wbmp':
                case 'bmp':
                    @imagewbmp($dstImg, $img);
                    break;
                case 'jpeg':
                case 'jpg':
                default:
                    @imagejpeg($dstImg, $img, 90);
            }
        }

        // clean
        imagedestroy($dstImg);
        imagedestroy($srcImg);
    }

    private function getImage($img, $imgInfo) {
        switch($imgInfo['mime']) {
            case 'image/jpeg':
                $srcImg = imagecreatefromjpeg($img);
                break;
            case 'image/png':
                $srcImg = imagecreatefrompng($img);
                break;
            case 'image/gif':
                $srcImg = imagecreatefromgif($img);
                break;
            case 'image/wbmp':
                $srcImg = imagecreatefromwbmp($img);
                break;
            default:
                throw new FileException('Not supported image type.');
        }

        return $srcImg;
    }

    private function setTransparency($dstImg, $srcImg) {
        $tIndex = imagecolortransparent($srcImg);
        $tColor = array('red' => 255, 'green' => 255, 'blue' => 255);
        if($tIndex >= 0) {
            $tColor = imagecolorsforindex($srcImg, $tIndex);
        }
        $tIndex = imagecolorallocate($dstImg, $tColor['red'], $tColor['green'], $tColor['blue']);
        imagefill($dstImg, 0, 0, $tIndex);
        imagecolortransparent($dstImg, $tIndex);
    }
}