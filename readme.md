[![Build Status](https://travis-ci.org/harrygr/OpenExchangeRater.svg?branch=master)](https://travis-ci.org/harrygr/OpenExchangeRater) [![Latest Stable Version](https://poser.pugx.org/harrygr/open-exchange-rater/v/stable)](https://packagist.org/packages/harrygr/open-exchange-rater) [![License](https://poser.pugx.org/harrygr/open-exchange-rater/license)](https://packagist.org/packages/harrygr/open-exchange-rater)

# Open Exchange Rater Client

This is a simple client to consume the [openexchangerate.org][1] REST API.

## Installation

Using composer:

    composer require harrygr/open-exchange-rater

Or clone the package into your app and include the `Client` class.

## Usage

Instantiate the client, passing in your app id:

    $client = new \OpenExchangeRater\Client($app_id);
    
You can now query the API like so:

#### Get an array of all available currencies:
    $client->currencies();

#### Get an array of the latest FX rates
    $client->latest();

#### Get an array of historical rates
    $client->historical(\DateTime $date);

#### Get a rate between 2 currencies
If a date is passed it will get the rate on that date. If not it will get the latest rate

    $client->rate($currency, $base, DateTime $date);

#### Convert a value from one currency to another
If a date is passed it will convert using the rate on that date. If not it will convert using the latest rate

    $client->convert($value, $from, $to, DateTime $date);

[1]: https://openexchangerates.org/