<?php

namespace Greenpacket\KiplePay\Events;

class SignFailed extends Event
{
    /**
     * Received data.
     *
     * @var array
     */
    public $data;

    /**
     * Bootstrap.
     *
     * @author Evans <evans.yang@greenpacket.com.cn>
     */
    public function __construct(string $gateway, array $data)
    {
        $this->data = $data;

        parent::__construct($gateway);
    }
}
