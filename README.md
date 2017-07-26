# Google Distance Matrix API
Estimate travel time and distance for multiple destinations.

Requirements
============
Requires PHP 7.0 or higher.


Installation
=============

The best way to install finalbytes/google-distance-matrix-api is using  [Composer](http://getcomposer.org/):

```sh
$ composer require finalbytes/google-distance-matrix-api
```

Getting Started
===============

```php

$distanceMatrix = new GoogleDistanceMatrix('YOUR API KEY');
$distance = $distanceMatrix
    ->setLanguage('nl-NL')
    ->addOrigin('51.458428,6.0541768')
    ->addDestination('48.139212,11.581721')
    ->addDestination('36.721184,-4.420084')
    ->sendRequest();

```

```php
$distanceMatrix = new GoogleDistanceMatrix('YOUR API KEY');
$distance = $distanceMatrix
    ->addOrigin('Van Bronckhorststraat 94, 5961SM Horst, The Netherlands')
    ->addDestination('Maistraße 10, 80337 München, Deutschland')
    ->setMode(GoogleDistanceMatrix::MODE_DRIVING)
    ->setLanguage('nl-NL')
    ->setUnits(GoogleDistanceMatrix::UNITS_METRIC)
    ->setAvoid(GoogleDistanceMatrix::AVOID_FERRIES)
    ->sendRequest();
```
    
For more info, please visit https://developers.google.com/maps/documentation/distance-matrix/