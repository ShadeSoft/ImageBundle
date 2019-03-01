<?php

namespace ShadeSoft\ImageBundle\Twig;

use ShadeSoft\GDImage\Exception\FileException;
use ShadeSoft\GDImage\Service\ImageSizer;
use Twig_Extension;
use Twig_SimpleFilter;

class FilterExtension extends Twig_Extension
{
    private $sizer;

    private $docroot;

    private $cacheDir;

    public function __construct(ImageSizer $sizer)
    {
        $this->sizer = $sizer;
        $this->docroot = $_SERVER['DOCUMENT_ROOT'];
        $this->cacheDir = null;
    }

    public function setConfig($cacheDir)
    {
        $this->cacheDir = $cacheDir;

        if ($this->cacheDir) {
            $this->sizer->getCache()->enable();
        }
    }

    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('widen', [$this, 'widen']),
            new Twig_SimpleFilter('heighten', [$this, 'heighten']),
            new Twig_SimpleFilter('maximize', [$this, 'maximize']),
            new Twig_SimpleFilter('thumbnail', [$this, 'thumbnail']),
        ];
    }

    public function widen($img, $width, $outputFormat = null, $targetPath = null)
    {
        if (!$targetPath && $this->cacheDir) {
            $targetPath = $this->cacheDir.$this->cacheFilename($img, "_w{$width}");
        }

        try {
            $this->sizer->widen($this->absPath($img), $width, $outputFormat,
                $targetPath ? ($this->absPath($targetPath)) : null);
        } catch (FileException $ex) {
            return '';
        }

        return $targetPath ?: $img;
    }

    public function heighten($img, $height, $outputFormat = null, $targetPath = null)
    {
        if (!$targetPath && $this->cacheDir) {
            $targetPath = $this->cacheDir.$this->cacheFilename($img, "_h{$height}");
        }

        try {
            $this->sizer->heighten($this->absPath($img), $height, $outputFormat,
               $targetPath ? ($this->absPath($targetPath)) : null);
        } catch (FileException $ex) {
            return '';
        }

        return $targetPath ?: $img;
    }

    public function maximize($img, $maxWidth, $maxHeight, $outputFormat = null, $targetPath = null)
    {
        if (!$targetPath && $this->cacheDir) {
            $targetPath = $this->cacheDir.$this->cacheFilename($img, "_m{$maxWidth}_{$maxHeight}");
        }

        try {
            $this->sizer->maximize($this->absPath($img), $maxWidth, $maxHeight, $outputFormat,
               $targetPath ? ($this->absPath($targetPath)) : null);
        } catch (FileException $ex) {
            return '';
        }

        return $targetPath ?: $img;
    }

    public function thumbnail($img, $width, $height, $outputFormat = null, $targetPath = null)
    {
        if (!$targetPath && $this->cacheDir) {
            $targetPath = $this->cacheDir.$this->cacheFilename($img, "_thumb{$width}x{$height}");
        }

        try {
            $this->sizer->thumbnail($this->absPath($img), $width, $height, $outputFormat,
                $targetPath ? ($this->absPath($targetPath)) : null);
        } catch (FileException $ex) {
            return '';
        }

        return $targetPath ?: $img;
    }

    private function cacheFilename($img, $appendix)
    {
        $xImg = explode('.', $img);
        $count = count($xImg);

        $filename = '';
        for ($i = 0; $i < $count - 1; ++$i) {
            $filename .= $xImg[$i];
        }

        return "{$filename}{$appendix}.".$xImg[$count - 1];
    }

    private function absPath($path)
    {
        return $this->docroot.$path;
    }
}
