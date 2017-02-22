# Image Bundle for Symfony

> An image editing bundle using PHP's GD library if other solutions fail for some reason.

## Installation

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require shadesoft/image-bundle "dev-master"
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

```php
<?php
// src/Acme/DemoController.php

// ...
class DemoController extends Controller
{
    public function DemoAction(Request $request) {
        // ...
        
        $imgSizer = $this->get('shadesoft_image.sizer');
        
        $img = '../../web/assets/temp.jpg';
        
        /** Functions have an optional last outputFormat parameter:
          * null as default or "jpeg"|"jpg"|"png"|"gif"|"wbmp"|"bmp"
          * if null given, system uses the source file's mime type
          * Be careful: functions don't rename your file to your new format */
        
        // Maximize image's size by its longest dimension while preserving its ratio
        $imgSizer->maximize($img, 800, 600);
        
        // Set an image to the given width while preserving its ratio
        $imgSizer->widen($img, 800);
        
        // Set an image to the given height while preserving its ratio
        $imgSizer->heighten($img, 600);
        
        // Crop and thumbnail functions coming soon.
        
        // ...
    }
    
    // ...
}
```