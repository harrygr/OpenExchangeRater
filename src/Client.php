<?php

namespace OpenExchangeRater;

use DateTime;

class Client
{
    private $app_id;

    /**
     * @var string
     */
    protected $baseUrl = "https://openexchangerates.org/api/";

    /**
     * Create an instance of the Open Exchange Rates rate API
     * 
     * @param string $app_id Your Open Exchange Rates App ID 
     */
    public function __construct($app_id)
    {
        $this->app_id = $app_id;
    }

    /**
     * Get the latest fx rates
     * @return array
     */
    public function latest()
    {
        return $this->getResponse('latest');
    }

    /**
     * Get historical fx rates
     * 
     * @param  DateTime $date The date for which to get the rates for
     * @return array
     */
    public function historical(DateTime $date)
    {
        return $this->getResponse('historical', $date);
    }

    /**
     * Get the available currencies
     * 
     * @return array
     */
    public function currencies()
    {
        return $this->getResponse('currencies');
    }

    /**
     * Get the rate from currency to another
     * E.g. rate('GBP', 'USD') => 0.65458
     * 
     * @param  string        $currency The 3-letter currency from which to get the rate
     * @param  string        $base     The 3-letter base currency
     * @param  DateTime|null $date     The date to use, if a historical rate is needed
     * @return float         The exchange rate
     */
    public function rate($currency, $base, DateTime $date = null)
    {
        if ($currency === $base) return 1;

        $rates = $date ? $this->historical($date) : $this->latest();

        $this->checkCurrencyIsValid($rates, $currency);
        $this->checkCurrencyIsValid($rates, $base);

        $currencyInUSD = $rates['rates'][$currency];
        $baseInUSD = $rates['rates'][$base];

        return $currencyInUSD / $baseInUSD;
    }

    /**
     * Convert a value from one currency to another
     * 
     * @param  float        $amount The value to convert
     * @param  string        $from   The currency the value is in
     * @param  string        $to     The currency to convert the value to
     * @param  DateTime|null $date   The date for which the exchange rate is from
     * @return float                The converted value
     */
    public function convert($value, $from, $to, DateTime $date = null)
    {
        $rate = $this->rate($from, $to, $date); 

        return $value / $rate;
    }

    /**
     * Build the url to perform the API request with
     * 
     * @param  $route The route 
     * @param  \DateTime $fxDate
     * @return string
     */
    protected function buildUrl($route = 'latest', DateTime $date = null)
    {
        if ($route == 'historical')
        {
            $endpoint = sprintf("%s/%s.json", $route, $date->format('Y-m-d'));            
        } else {
            $endpoint = sprintf("%s.json", $route);                        
        }

        return $this->baseUrl . $endpoint . '?app_id=' . $this->app_id;
    }

    /**
     * Send off the request and return the response
     *
     * @param  string        $route The API endpoint filename
     * @param  DateTime|null $date  The date parameter if required
     * @return array                The response decoded into an array
     */
    protected function getResponse($route, DateTime $date = null)
    {
        if ($json = file_get_contents($this->buildUrl($route, $date)))
            return json_decode($json, 1);
    }

    /**
     * Check a currency exists in the API response
     * 
     * @param  array $rates    The API reponse
     * @param  string $currency currency to check
     * @throws InvalidCurrencyException
     */
    protected function checkCurrencyIsValid($rates, $currency)
    {
        if (!isset($rates['rates'][$currency]))
            throw new InvalidCurrencyException("The currency \"$currency\" is invalid.");
    }
}
