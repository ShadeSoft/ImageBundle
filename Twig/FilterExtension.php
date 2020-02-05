<?php

namespace ShadeSoft\ImageBundle\Twig;

use ShadeSoft\GDImage\Exception\FileException;
use ShadeSoft\GDImage\CachedSizer;
use ShadeSoft\GDImage\Converter;
use ShadeSoft\GDImage\Sizer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FilterExtension extends AbstractExtension
{
    private $converter;

    private $sizer;

    private $docroot;

    private $cacheDir;

    public function __construct()
    {
        $this->converter = new Converter;
        $this->sizer     = new Sizer;
        $this->docroot   = $_SERVER['DOCUMENT_ROOT'];
        $this->cacheDir  = null;
    }

    public function setConfig($cacheDir)
    {
        $this->cacheDir = $cacheDir;

        if ($this->cacheDir) {
            $this->sizer = new CachedSizer;
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
                    $ni = $this->sizer->image($this->absPath($img))->widen($width);

                    if ($outputFormat) {
                        $to = 'to'.ucfirst($outputFormat);
                        $ni->$to();
                    }

                    if ($targetPath) {
                        $ni->target($this->absPath($targetPath));
                    }

                    $ni->save();
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
                    $ni = $this->sizer->image($this->absPath($img))->heighten($height);

                    if ($outputFormat) {
                        $to = 'to'.ucfirst($outputFormat);
                        $ni->$to();
                    }

                    if ($targetPath) {
                        $ni->target($this->absPath($targetPath));
                    }

                    $ni->save();
                } catch (FileException $ex) {
                    return '';
                }

                return $targetPath ?: $img;
            }),
            new TwigFilter('maximize', function ($img, $width, $height, $outputFormat = null, $targetPath = null) {
                if (!$targetPath && $this->cacheDir) {
                    $targetPath = $this->cacheDir.$this->cacheFilename($img, "_m{$width}_{$height}", $outputFormat);
                }

                try {
                    $ni = $this->sizer->image($this->absPath($img))->maximize($width, $height);

                    if ($outputFormat) {
                        $to = 'to'.ucfirst($outputFormat);
                        $ni->$to();
                    }

                    if ($targetPath) {
                        $ni->target($this->absPath($targetPath));
                    }

                    $ni->save();
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
                    $ni = $this->sizer->image($this->absPath($img))->thumbnail($width, $height);

                    if ($outputFormat) {
                        $to = 'to'.ucfirst($outputFormat);
                        $ni->$to();
                    }

                    if ($targetPath) {
                        $ni->target($this->absPath($targetPath));
                    }

                    $ni->save();
                } catch (FileException $ex) {
                    return '';
                }

                return $targetPath ?: $img;
            }),
            new TwigFilter('jpg', function ($img, $targetPath = null, $quality = null) {
                return $this->convert($img, 'jpg', $targetPath ?: "$img.jpg", $quality);
            }),
            new TwigFilter('png', function ($img, $targetPath = null, $quality = null) {
                return $this->convert($img, 'png', $targetPath ?: "$img.png", $quality);
            }),
            new TwigFilter('gif', function ($img, $targetPath = null, $quality = null) {
                return $this->convert($img, 'gif', $targetPath ?: "$img.gif", $quality);
            }),
            new TwigFilter('bmp', function ($img, $targetPath = null, $quality = null) {
                return $this->convert($img, 'bmp', $targetPath ?: "$img.bmp", $quality);
            }),
            new TwigFilter('webp', function ($img, $targetPath = null, $quality = null) {
                return $this->convert($img, 'webp', $targetPath ?: "$img.webp", $quality);
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

        return $filename."$appendix.".($format ?: $xImg[$count - 1]);
    }

    private function absPath($path)
    {
        return $this->docroot.$path;
    }

    private function convert($img, $format, $targetPath, $quality = null) {
        try {
            $to = 'to'.ucfirst($format);
            $ni = $this->converter->image($img)
                ->$to()
                ->target($this->absPath($targetPath));

            if ($quality) {
                $ni->quality($quality);
            }

            $ni->save();
        } catch (FileException $ex) {
            return '';
        }

        return $targetPath;
    }
}
