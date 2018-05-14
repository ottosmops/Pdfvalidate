# Validate a PDF with pdftocairo

[![GitHub license](https://img.shields.io/github/license/ottosmops/pdfvalidate.svg)](https://github.com/ottosmops/pdfvalidate/blob/master/LICENSE.md)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ottosmops/pdfvalidate/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ottosmops/pdfvalidate/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/ottosmops/pdfvalidate/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ottosmops/pdfvalidate/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/ottosmops/pdfvalidate/badges/build.png?b=master)](https://scrutinizer-ci.com/g/ottosmops/pdfvalidate/build-status/master)
[![Latest Stable Version](https://poser.pugx.org/ottosmops/pdfvalidate/v/stable?format=flat-square)](https://packagist.org/packages/ottosmops/pdfvalidate)
[![Packagist Downloads](https://img.shields.io/packagist/dt/ottosmops/pdfvalidate.svg?style=flat-square)](https://packagist.org/packages/ottosmops/pdfvalidate)

This package provides a very simple PDF Validator. In fact you can only check if the Pdf is readable by ```pdftocairo``` without problems. 

```php
$validator = new \Ottosmops\Pdfvalidate\Validator('/path/to/file.pdf');  
if (!$validator->check()) {
    echo $validator->error;
    echo $validator->output; // original information
    exit(1);
} 

// the pdf should be ok
// do something useful ...
```
This is the command which is used behind the scene: ```pdftocairo -pdf path/to/file - 2>&1 >/dev/null```.

## Requirements

The Package uses [pdftocairo](https://linux.die.net/man/1/pdftocairo). Make sure that this is installed: ```which pdftocairo```

For Installation see:
[poppler-utils](https://linuxappfinder.com/package/poppler-utils)

If the installed binary is not found ("```The command "which pdftoppm" failed.```") you can pass the full path to the ```_constructor``` (see below) or use ```putenv('PATH=$PATH:/usr/local/bin/:/usr/bin')``` (with the dir where pdftoppm lives) before you call the class ```Converter```.

## Installation

```bash
composer require ottosmops/pdfvalidate
```


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

