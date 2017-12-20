# Image Bundle for Symfony

> An image editing bundle using PHP's GD library if other solutions fail for some reason.

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

For usage details, please check the
['Parameters' and 'Available functions' sections](https://github.com/ShadeSoft/GDImage/blob/master/README.md#parameters)
of the shadesoft/gd-image documentation.
