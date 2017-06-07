# Doppy's GoogleTagManagerBundle

This bundle allows an easy implementation of the Google Tag Manager into your web application.

## Requirements

- PHP 5.6 or PHP 7.0 or higher.
- Symfony 3.0 or higher

## Installation

### Add to composer

````
$ composer require doppy/google-tag-manager-bundle

````

### Add to AppKernel

````
public function registerBundles()
{
    $bundles = array(
        // ...
        new Doppy\GoogleTagManagerBundle\DoppyGoogleTagManagerBundle(),
        // ...
    );
}
````

### Add configuration

````
# config_xxx.yml
doppy_google_tag_manager:
    tag_id:  GTM-ABCDEF
    enabled: true 
    test:    false
````

Set `enabled` to false to disable all output. No script tags will be rendered. You may get console-errors when you manually call `dataLayer.push`

Set `test` to true to enable test-mode. This mode still outputs everything in your template, but the working will be disabled (see twig functions below). 

## Template configuration

To use the bundle, you need a few simple calls to a couple of twig functions in your templates.

### Header

Add the following to your head section in your base template.
````
{{ doppy_gtm_script() }}
````
This will load the external javascript supplied by Google Tag Manger.

When test mode is enabled, this script will be wrapped in comment, disabling the working. An empty `dataLayer` array will be initialized with an additional script-tag to avoid console errors.


### Body

Add the following directly after your body-tag.
````
{{ doppy_gtm_noscript() }}
````
This renders the noscript iframe tag for users that have javascript disabled.

When test mode is enabled, this will be wrapped in comment, disabling the working.

### DataLayer push

When you need to push data on a specific page you can do the following in the template for that page.

````
{{ doppy_gtm_push({'your-key': 'your-value'}) }}
````
This will add a script tag to the output which will call `dataLayer.push`.
You can add any data as values, as long as it is an array or serializable (see Data Serializers).

### Serialize and Normalize

When you need some serialized data in javascript for click events, or in cases you don't want to add the data on pageload, there are 2 twig functions available for you that can help you in this case.

````
{{ doppy_gtm_serialize({'your-key': 'your-value'}) }}
{{ doppy_gtm_normalize({'your-key': 'your-value'}) }}
````
The serialize function will simply serialize the given data and give it back as correctly formatted json. Allowing you to use it anywhere you want.
The normalize function will return the normalized array, allowing you to make additional changes before serializing to json if you want to.

## Data Serializers

You can configure Normalizers for support for objects. This way you can simply pass a specific object from your application into the `doppy_gtm_push` function and it will be converted to json that is useful.

For every object you might pass to the tag manager functions you can write a `Normalizer`, which should implement `Symfony\Component\Serializer\Normalizer\NormalizerInterface`.
This normalizer should create a flat array containing only data that the Google Tag Manager needs.

Define this normalizer as a service within Symfony and tag it as follows:

````
services:
    your_bundle.xxx_normalizer:
        class: YourBundle\Serializer\Normalizer\XxxNormalizer
        tags:
            - { name: "doppy_google_tag_manager.normalizer" }
````

This will add this Normalizer to the serializer used within the Manager and automatically convert your objects for your needs.

Now you can pass objects to the `doppy_gtm_push`, `doppy_gtm_serialize` and `doppy_gtm_normalize` functions and they will automatically be converted.

For more information about Symfony Normalizers see http://symfony.com/doc/current/components/serializer.html .

