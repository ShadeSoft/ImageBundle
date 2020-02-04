<?php

namespace ShadeSoft\ImageBundle\Twig;

use ShadeSoft\GDImage\Exception\FileException;
use ShadeSoft\GDImage\Service\ImageSizer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FilterExtension extends AbstractExtension
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
            new TwigFilter('widen', function ($img, $width, $outputFormat = null, $targetPath = null) {
                if (!$targetPath && $this->cacheDir) {
                    $targetPath = $this->cacheDir.$this->cacheFilename($img, "_w{$width}", $outputFormat);
                }

                try {
                    $this->sizer->widen($this->absPath($img), $width, $outputFormat,
                        $targetPath ? ($this->absPath($targetPath)) : null);
                } catch (FileException $ex) {
                    return '';
                }

                return $targetPath ?: $img;
            }),
            new TwigFilter('heighten', function ($img, $height, $outputFormat = null, $targetPath = null) {
                if (!$targetPath && $this->cacheDir) {
                    $targetPath = $this->cacheDir.$this->cacheFilename($img, "_h{$height}", $outputFormat);
                }

                try {
                    $this->sizer->heighten($this->absPath($img), $height, $outputFormat,
                       $targetPath ? ($this->absPath($targetPath)) : null);
                } catch (FileException $ex) {
                    return '';
                }

                return $targetPath ?: $img;
            }),
            new TwigFilter('maximize', function ($img, $maxWidth, $maxHeight, $outputFormat = null, $targetPath = null) {
                if (!$targetPath && $this->cacheDir) {
                    $targetPath = $this->cacheDir.$this->cacheFilename($img, "_m{$maxWidth}_{$maxHeight}", $outputFormat);
                }

                try {
                    $this->sizer->maximize($this->absPath($img), $maxWidth, $maxHeight, $outputFormat,
                       $targetPath ? ($this->absPath($targetPath)) : null);
                } catch (FileException $ex) {
                    return '';
                }

                return $targetPath ?: $img;
            }),
            new TwigFilter('thumbnail', function ($img, $width, $height, $outputFormat = null, $targetPath = null) {
                if (!$targetPath && $this->cacheDir) {
                    $targetPath = $this->cacheDir.$this->cacheFilename($img, "_thumb{$width}x{$height}", $outputFormat);
                }

                try {
                    $this->sizer->thumbnail($this->absPath($img), $width, $height, $outputFormat,
                        $targetPath ? ($this->absPath($targetPath)) : null);
                } catch (FileException $ex) {
                    return '';
                }

                return $targetPath ?: $img;
            }),
        ];
    }

    private function cacheFilename($img, $appendix, $format = null)
    {
        $xImg = explode('.', $img);
        $count = count($xImg);

        $filename = '';
        for ($i = 0; $i < $count - 1; ++$i) {
            $filename .= $xImg[$i];
        }

        return "{$filename}{$appendix}.".($format ?: $xImg[$count - 1]);
    }

    private function absPath($path)
    {
        return $this->docroot.$path;
    }
}
