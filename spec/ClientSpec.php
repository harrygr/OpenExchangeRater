<?php

namespace spec\OpenExchangeRater;

use DateTime;
use Prophecy\Argument;
use PhpSpec\ObjectBehavior;

class ClientSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(getenv('OPEN_EXCHANGE_RATES_APP_ID'));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('OpenExchangeRater\Client');
    }

    function it_gets_the_available_currencies()
    {
        $this->currencies()->shouldHaveKey('USD');
    }

    function it_gets_the_latest_fx_rates()
    {
        $this->latest()->shouldHaveKey('rates');
    }

    function it_gets_historical_fx_rates()
    {
        $this->historical(new DateTime('2015-01-15'))->shouldHaveKey('rates');
    }

    function it_gets_the_latest_fx_rate_for_a_given_currency_pair()
    {
        $this->rate('GBP', 'EUR')->shouldBeFloat();
        $this->rate('GBP', 'USD')->shouldBeFloat();
    }

    function it_gets_the_historical_fx_rate_for_a_given_currency_pair()
    {
        $date = new DateTime('2015-01-15');
        $this->rate('GBP', 'EUR', $date)->shouldBeFloat();
        $this->rate('GBP', 'USD', $date)->shouldBeFloat();
    }

    function it_takes_exception_to_an_invalid_currency()
    {
        $this->shouldThrow('\OpenExchangeRater\InvalidCurrencyException')->during('rate', ['GBP', 'XXX']);
    }

    function it_converts_a_value_from_one_currency_to_another()
    {
        $this->convert(10, 'GBP', 'USD')->shouldBeFloat();
        $this->convert(10, 'GBP', 'USD', new DateTime('2015-01-15'))->shouldBeFloat();
    }
}
