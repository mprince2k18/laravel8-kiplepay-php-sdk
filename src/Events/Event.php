<?php

namespace Greenpacket\KiplePay\Events;

use Symfony\Contracts\EventDispatcher\Event as SymfonyEvent;

class Event extends SymfonyEvent
{

    /**
     * Gateway.
     *
     * @var string
     */
    public $gateway;

    /**
     * Extra attributes.
     *
     * @var mixed
     */
    public $attributes;

    /**
     * Bootstrap.
     *
     * @author Evans <evans.yang@greenpacket.com.cn>
     */
    public function __construct(string $gateway)
    {
        $this->gateway = $gateway;
    }
}
