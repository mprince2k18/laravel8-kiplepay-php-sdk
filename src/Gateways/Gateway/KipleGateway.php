<?php

namespace Greenpacket\KiplePay\Gateways\Gateway;

use Greenpacket\KiplePay\Supports\Collection;
use Greenpacket\KiplePay\Contracts\GatewayInterface;
use Greenpacket\KiplePay\Exceptions\InvalidArgumentException;

abstract class KipleGateway implements GatewayInterface
{

  /**
   * Bootstrap.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @throws InvalidArgumentException
   */
  public function __construct()
  {
  }

  /**
   * Pay an order.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param string $endpoint
   *
   * @return Collection
   */
  abstract public function gateway($endpoint, array $payload);
}
