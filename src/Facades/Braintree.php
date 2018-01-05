<?php
namespace Dbws\Braintree\Facades;

/**
 * @see \Illuminate\Routing\UrlGenerator
 */

use Illuminate\Support\Facades\Facade;

class Braintree extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Braintree::class;
    }
}