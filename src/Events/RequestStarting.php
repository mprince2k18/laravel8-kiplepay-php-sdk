<?php

namespace Greenpacket\KiplePay\Events;

class RequestStarting extends Event
{
    /**
     * Params.
     *
     * @var array
     */
    public $params;

    /**
     * Bootstrap.
     */
    public function __construct(string $gateway, array $params)
    {
        $this->params = $params;

        parent::__construct($gateway);
    }
}
