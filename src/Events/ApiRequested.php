<?php

namespace Greenpacket\KiplePay\Events;

class ApiRequested extends Event
{
    /**
     * Endpoint.
     *
     * @var string
     */
    public $endpoint;

    /**
     * Result.
     *
     * @var array
     */
    public $result;

    /**
     * Bootstrap.
     */
    public function __construct(string $gateway, string $endpoint, array $result)
    {
        $this->endpoint = $endpoint;
        $this->result = $result;

        parent::__construct($gateway);
    }
}
