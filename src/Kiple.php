<?php

namespace Greenpacket\KiplePay;

use Exception;
use Greenpacket\KiplePay\Supports\Log;
use Greenpacket\KiplePay\Supports\Str;
use Greenpacket\KiplePay\Supports\Logger;
use Greenpacket\KiplePay\Supports\Config;
use Greenpacket\KiplePay\Gateways\Gateway;
use Greenpacket\KiplePay\Listeners\KernelLogSubscriber;
use Greenpacket\KiplePay\Exceptions\InvalidGatewayException;
use Greenpacket\KiplePay\Contracts\GatewayApplicationInterface;

class Kiple
{
  
  /**
   * Config.
   *
   * @var Config
   */
  protected $config;

  /**
   * Bootstrap.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @throws Exception
   */
  public function __construct(array $config)
  {
    $this->config = new Config($config);

    $this->registerLogService();
    $this->registerEventService();
  }

  /**
   * Magic static call.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param string $method
   * @param array  $params
   *
   * @throws InvalidGatewayException
   * @throws Exception
   */
  public static function __callStatic($method, $params): GatewayApplicationInterface
  {
    $app = new self(...$params);

    return $app->create($method);
  }

  /**
   * Create a instance.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param string $method
   *
   * @throws InvalidGatewayException
   */
  protected function create($method): GatewayApplicationInterface
  {

    $gateway = __NAMESPACE__.'\\Gateways\\'.Str::studly($method);
    if (class_exists($gateway)) {
      return self::make($gateway);
    }

    throw new InvalidGatewayException("Gateway [{$method}] Not Exists");
  }

  /**
   * Make a gateway.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param string $gateway
   *
   * @throws InvalidGatewayException
   */
  protected function make($gateway)
  {
    $app = new $gateway($this->config);

    if ($app instanceof GatewayApplicationInterface) {
      return $app;
    }

    throw new InvalidGatewayException("Gateway [{$gateway}] Must Be An Instance Of GatewayApplicationInterface");
  }

  /**
   * Register log service.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @throws Exception
   */
  protected function registerLogService()
  {
    $config = $this->config->get('log');
    $config['identify'] = 'greenpacke.kiple-sdk';

    $logger = new Logger();
    $logger->setConfig($config);

    Log::setInstance($logger);
  }

  /**
   * Register event service.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   */
  protected function registerEventService()
  {
    Events::setDispatcher(Events::createDispatcher());

    Events::addSubscriber(new KernelLogSubscriber());
  }
}
