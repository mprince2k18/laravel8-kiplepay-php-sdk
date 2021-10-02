<?php

namespace Greenpacket\KiplePay\Events;

class MethodCalled extends Event
{
    /**
     * endpoint.
     *
     * @var string
     */
    public $endpoint;

    /**
     * payload.
     *
     * @var array
     */
    public $payload;

    /**
     * Bootstrap.
     *
     * @author Evans <evans@greenpacket.com.cn>
     */
    public function __construct(string $gateway, string $endpoint, array $payload = [])
    {
        $this->endpoint = $endpoint;
        $this->payload = $payload;

        parent::__construct($gateway);
    }
}
