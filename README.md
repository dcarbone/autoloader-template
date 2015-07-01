# autoloader-template
Simple Autoloader template for use in PHP 5.3+ projects

## Purpose
This class is designed to provide a reference implementation for setting up an Autoloader class for a set of PHP classes.

For more information on the base concept of autoloading, please refer to [PHP's official documentation on the matter](http://php.net/manual/en/language.oop5.autoload.php).  You may also want to familiarize yourself with the [spl_autoload_register](http://php.net/manual/en/function.spl-autoload-register.php) function, as well as the other spl_autoload_* functions.

## Implementation
Out of the box it will not work without modification.  A few things to modify:

#### Class Name
The name of this class should be somewhat indicative of what classes it is responsible for autoloading.  For instance, I use a version of this template in my CurlPlus lib, where I renamed the class [CurlPlusAutoloader](https://github.com/dcarbone/curl-plus/blob/master/src/CurlPlusAutoLoader.php).  The actual name is entirely up to you.

#### ROOT_NAMESPACE_OF_CONERN
This value should be changed to the portion(s) of the target namespace that is common.  That is to say, if you have a set of classes such as:

- Food\Delicious\Sandwich
- Food\Delicious\Spaghetti
- Food\Delicious\Soup

..the largest common namespace segment would be "Food\Delicious".  This limits this autoloader implementation to only attempt to load into memory classes that you specifically target.  If a class name comes into the loader that does not start with this namespace prefix it will immediately return null, indicating to PHP that this autoloader is not able to load this class and to move on.

You may make this more generic, if you wish.  In the above example, it would be acceptable to set a ROOT_NAMESPACE_OF_CONCERN value of "Food" depending on your implementation.  More on this below.

#### ROOT_DIRECTORY_OF_CONCERN
This value should correspond with the root directory containing the set of classes you want to have this autoloader load up.  I would recommend making this an absolute path, or base it off of magic constants such as `__DIR__`.

This is used in conjunction with the ROOT_NAMESPACE_OF_CONCERN constant to actually locate the classes, and expects you to have certain structures in place.  More on this below.

## Directory Structure & Namespaces
[Composer](https://getcomposer.org/) is an increasingly popular dependency management tool that also has an autoloading component, and I highly suggest looking into making your project / library packagable within Composer at some point.  It is super cool.

With that said, this template class attempts to utilize 2 different directory structures which I modeled based on work I've done with Composer.

#### PSR-0
With a set of code in PSR-0 structure, each portion of a namespace must correspond exactly (capitalization, spelling, etc.) with a directory.

Using the above example, your directory structure should look a little something like this:

```
|-- src/
|   |-- Food/
|   |   |-- Delicious/
|   |   |   |-- Sandwich.php
|   |   |   |-- Soup.php
|   |   |   |-- Spaghetti.php
```

In this setup, you could use the following:

```php
const ROOT_DIRECTORY_OF_CONCERN = 'path-to-src';
const ROOT_NAMESPACE_OF_CONCERN = 'Food\\Delicious';
```

The `ROOT_NAMESPACE_OF_CONCERN` value has less significance here, as it is only used to determine if this autoloader should even ATTEMPT to handle loading this class.  You should still attempt to define a value that you do not think would interfere with another autoloader / library you're including in your project.

#### PSR-4
PSR-4 is either great or evil, depending on who you ask.  It allows you to not create directories that only contain other directories by assuming that everything under a certain directory has a specific namespace prefix.

Using the above example again, the directory structure could look like this:

```
|-- src/
|   |-- Sandwich.php
|   |-- Soup.php
|   |-- Spaghetti.php
```

In this setup, you would need to specify the following:

```php
const ROOT_DIRECTORY_OF_CONCERN = 'path-to-src';
const ROOT_NAMESPACE_OF_CONCERN = 'Food\\Delicious';
```

It looks the same as the PSR-0 definition above, however in this case the `ROOT_NAMESPACE_OF_CONCERN` value is incredibly important.  Because the autoloader can no longer safely rely on implicitly knowing the directory structure based off of the namespace alone, we must tell PHP what portion of the namespace to modify to get to the file containing the class.

In this case, because all of our classes share a root namespace of `Food\Delicious`, we can put all the class files into the root of the `src/` directory, and specify a root namespace of `Food\Delicious` safely.

## Features & Additional Work
I would like to keep this class as simple as possible.  I am, however, willing to accept suggestions in how it could be made easier for newcomers to PHP or experienced PHP developers new to the idea of autoloading, or just people who have an idea of how to do something better!  Any comments / cricitisms welcome.
