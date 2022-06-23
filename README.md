# Image Bundle for Symfony

> ABANDONED - Please use [GDImage](https://github.com/ShadeSoft/GDImage) instead.
> 
> An image editing bundle using PHP's GD library if other solutions fail for some reason.

[![Latest Stable Version](https://poser.pugx.org/shadesoft/image-bundle/version)](https://packagist.org/packages/shadesoft/image-bundle)
[![Build Status](https://travis-ci.org/ShadeSoft/ImageBundle.svg)](https://travis-ci.org/ShadeSoft/ImageBundle)
[![StyleCI](https://styleci.io/repos/82859264/shield?style=flat)](https://styleci.io/repos/82859264)
[![Total Downloads](https://poser.pugx.org/shadesoft/image-bundle/downloads)](https://packagist.org/packages/shadesoft/image-bundle)
[![License](https://poser.pugx.org/shadesoft/image-bundle/license)](https://packagist.org/packages/shadesoft/image-bundle)

## Installation

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require shadesoft/image-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new ShadeSoft\ImageBundle\ShadeSoftImageBundle(),
        );

        // ...
    }

    // ...
}
```

## Usage:

### Usage with PHP

```php
<?php
// src/Acme/DemoController.php

// ...
class DemoController extends Controller
{
    public function DemoAction(Request $request) {
        $img = '../../web/assets/temp.jpg';

        $imgSizer = $this->get('shadesoft_image.sizer');

        $imgSizer->thumbnail($img, 400, 300);
    }
}
```

### Usage with Twig

```twig
{{ asset('/path/to/image'|thumbnail(400, 300)) }}
{# recommended to use with cache functionality enabled, see below #}
```

For usage details, please check the
['Parameters' and 'Available functions' sections](https://github.com/ShadeSoft/GDImage/blob/master/README.md#parameters)
of the shadesoft/gd-image documentation.

## Cache

To enable cache, you just need to configure the bundle like below:

```yaml
# app/config/config.yml for Symfony 2/3, and config/packages/shade_soft_image.yaml for Symfony 4

shade_soft_image:
    cache_directory: /path/to/desired/cache/directory
```
