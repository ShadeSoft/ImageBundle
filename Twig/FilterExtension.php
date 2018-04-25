<?php

namespace ShadeSoft\ImageBundle\Twig;

use ShadeSoft\GDImage\Exception\FileException;
use ShadeSoft\GDImage\Service\ImageSizer;
use Twig_Extension;
use Twig_SimpleFilter;

class FilterExtension extends Twig_Extension {

    private $sizer,
            $docroot;

    public function __construct(ImageSizer $sizer) {
        $this->sizer = $sizer;
        $this->docroot = $_SERVER['DOCUMENT_ROOT'];
    }

    public function getFilters() {
        return array(
            new Twig_SimpleFilter('widen', array($this, 'widen')),
            new Twig_SimpleFilter('heighten', array($this, 'heighten')),
            new Twig_SimpleFilter('maximize', array($this, 'maximize')),
            new Twig_SimpleFilter('thumbnail', array($this, 'thumbnail'))
        );
    }

    public function widen($img, $width, $outputFormat = null, $targetPath = null) {
        try {
            $this->sizer->widen($this->docroot . $img, $width, $outputFormat, $this->docroot . $targetPath);
        } catch(FileException $ex) {
            return '';
        }

        return $targetPath ?: $img;
    }

    public function heighten($img, $height, $outputFormat = null, $targetPath = null) {
        try {
            $this->sizer->heighten($this->docroot . $img, $height, $outputFormat, $this->docroot . $targetPath);
        } catch(FileException $ex) {
            return '';
        }

        return $targetPath ?: $img;
    }

    public function maximize($img, $maxWidth, $maxHeight, $outputFormat = null, $targetPath = null) {
        try {
            $this->sizer->maximize($this->docroot . $img, $maxWidth, $maxHeight, $outputFormat, $this->docroot . $targetPath);
        } catch(FileException $ex) {
            return '';
        }

        return $targetPath ?: $img;
    }

    public function thumbnail($img, $width, $height, $outputFormat = null, $targetPath = null) {
        try {
            $this->sizer->thumbnail($this->docroot . $img, $width, $height, $outputFormat, $this->docroot . $targetPath);
        } catch(FileException $ex) {
            return '';
        }

        return $targetPath ?: $img;
    }
}