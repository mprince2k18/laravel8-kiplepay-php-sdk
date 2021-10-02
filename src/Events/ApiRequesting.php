<?php

namespace Greenpacket\KiplePay\Events;

class ApiRequesting extends Event
{
    /**
     * Endpoint.
     *
     * @var string
     */
    public $endpoint;

    /**
     * Payload.
     *
     * @var array
     */
    public $payload;

    /**
     * Bootstrap.
     */
    public function __construct(string $gateway, string $endpoint, array $payload)
    {
        $this->endpoint = $endpoint;
        $this->payload = $payload;

        parent::__construct($gateway);
    }
}
