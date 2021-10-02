<?php

namespace Greenpacket\KiplePay;

use Greenpacket\KiplePay\Supports\Log AS BaseLogger;

/**
 * @method static void emergency($message, array $context = array())
 * @method static void alert($message, array $context = array())
 * @method static void critical($message, array $context = array())
 * @method static void error($message, array $context = array())
 * @method static void warning($message, array $context = array())
 * @method static void notice($message, array $context = array())
 * @method static void info($message, array $context = array())
 * @method static void debug($message, array $context = array())
 * @method static void log($message, array $context = array())
 */
class Logger
{
  /**
   * Forward call.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param string $method
   * @param array  $args
   *
   * @return mixed
   */
  public static function __callStatic($method, $args)
  {
    return forward_static_call_array([BaseLogger::class, $method], $args);
  }

  /**
   * Forward call.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param string $method
   * @param array  $args
   *
   * @return mixed
   */
  public function __call($method, $args)
  {
    return call_user_func_array([BaseLogger::class, $method], $args);
  }
}