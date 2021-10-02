<?php

namespace Greenpacket\KiplePay\Gateways\Gateway;

use Greenpacket\KiplePay\Events;
use Greenpacket\KiplePay\Logger;
use Greenpacket\KiplePay\Supports\Str;
use Greenpacket\KiplePay\Supports\Arr;
use Greenpacket\KiplePay\Supports\Config;
use Greenpacket\KiplePay\Gateways\Gateway;
use Greenpacket\KiplePay\Supports\Collection;
use Greenpacket\KiplePay\Exceptions\GatewayException;
use Greenpacket\KiplePay\Supports\Traits\HasHttpRequest;
use Greenpacket\KiplePay\Exceptions\InvalidSignException;
use Greenpacket\KiplePay\Exceptions\InvalidConfigException;

/**
 * @author Evans <evans.yang@greenpacket.com.cn>
 *
 * @property string app_id merchant app_id
 * @property string public_key
 * @property string private_key
 * @property array http http options
 * @property array log log options
 */
class Support
{
  use HasHttpRequest;

  /**
   * gateway.
   *
   * @var string
   */
  protected $baseUri;

  /**
   * Config.
   *
   * @var Config
   */
  protected $config;

  /**
   * Instance.
   *
   * @var Support
   */
  private static $instance;

  /**
   * Bootstrap.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param Config $config
   */
  private function __construct(Config $config)
  {
    $this->baseUri = $config->get('endpoint');
    $this->config = $config;
    $this->setHttpOptions();
  }

  /**
   * __get.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param $key
   *
   * @return mixed|null|Config
   */
  public function __get($key)
  {
    return $this->getConfig($key);
  }

  /**
   * create.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param Config $config
   *
   * @return Support
   */
  public static function create(Config $config)
  {
    if (php_sapi_name() === 'cli' || !(self::$instance instanceof self)) {
      self::$instance = new self($config);
    }

    return self::$instance;
  }

  /**
   * clear.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @return void
   */
  public function clear()
  {
    self::$instance = null;
  }

  /**
   * Get Gateway API result.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param array $data
   *
   * @throws GatewayException
   * @throws InvalidConfigException
   * @throws InvalidSignException
   *
   * @return Collection
   */
  public static function requestApi(string $method,string $endpoint,array $data): Collection
  {
    Events::dispatch(new Events\ApiRequesting(Str::studly($method), self::$instance->getBaseUri(), $data));

    $data = array_filter($data, function ($value) {
      return ($value == '' || is_null($value)) ? false : true;
    });

    if($method == 'get'){
      $result = self::$instance->get($endpoint, $data);
    }else if($method == 'post'){
      $params = "biz_content=".urlencode($data['biz_content']);
      $result = self::$instance->post($endpoint, $params);
    }else if($method == 'files'){
      $result = self::$instance->post($endpoint, $data,'file');
    }
    
    if(gettype($result) !== 'array'){
      $result = json_decode($result, true);
    }
    
    Events::dispatch(new Events\ApiRequested(Str::studly($method), self::$instance->getBaseUri(), $result));
    return new Collection($result);
  }

  /**
   * Generate sign.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param array $params
   *
   * @throws InvalidConfigException
   *
   * @return string
   */
  public static function generateSign(array $params): string
  {
    $privateKey = self::$instance->private_key;

    if (is_null($privateKey)) {
      throw new InvalidConfigException('Missing Config -- [private_key]');
    }

    if (Str::endsWith($privateKey, '.pem')) {
      $privateKey = openssl_pkey_get_private($privateKey);
    } else {
      $privateKey = "-----BEGIN RSA PRIVATE KEY-----\n".
          wordwrap($privateKey, 64, "\n", true).
          "\n-----END RSA PRIVATE KEY-----";
    }

    openssl_sign(self::getSignContent($params), $sign, $privateKey, OPENSSL_ALGO_SHA256);

    $sign = base64_encode($sign);


    Logger::debug('The Request Generate Sign', [$params, $sign]);

    if (is_resource($privateKey)) {
      openssl_free_key($privateKey);
    }
    return $sign;
  }

  /**
   * Verify sign.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param array       $data
   * @param bool        $sync
   * @param string|null $sign
   *
   * @throws InvalidConfigException
   *
   * @return bool
   */
  public static function verifySign(array $data, $sync = false, $sign = null): bool
  {
    $publicKey = self::$instance->public_key;

    if (is_null($publicKey)) {
        throw new InvalidConfigException('Missing Config -- [public_key]');
    }

    if (Str::endsWith($publicKey, '.pem')) {
        $publicKey = openssl_pkey_get_public($publicKey);
    } else {
        $publicKey = "-----BEGIN PUBLIC KEY-----\n".
            wordwrap($publicKey, 64, "\n", true).
            "\n-----END PUBLIC KEY-----";
    }

    $sign = $sign ?? $data['sign'];

    $toVerify = $sync ? mb_convert_encoding(json_encode($data, JSON_UNESCAPED_UNICODE), 'gb2312', 'utf-8') : self::getSignContent($data, true);

    $isVerify = openssl_verify($toVerify, base64_decode($sign), $publicKey, OPENSSL_ALGO_SHA256) === 1;

    if (is_resource($publicKey)) {
        openssl_free_key($publicKey);
    }

    return $isVerify;
  }

  /**
   * Get signContent that is to be signed.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param array $data
   * @param bool  $verify
   *
   * @return string
   */
  public static function getSignContent(array $data, $verify = false): string
  {
    $data = self::encoding($data, $data['charset'] ?? 'gb2312', 'utf-8');

    ksort($data);

    $stringToBeSigned = '';
    foreach ($data as $k => $v) {
        if ($verify && $k != 'sign' && $k != 'sign_type') {
            $stringToBeSigned .= $k.'='.$v.'&';
        }
        if (!$verify && $v !== '' && !is_null($v) && $k != 'sign' && '@' != substr($v, 0, 1)) {
            $stringToBeSigned .= $k.'='.$v.'&';
        }
    }
    Logger::debug('Generate Sign Content Before Trim', [$data, $stringToBeSigned]);

    return trim($stringToBeSigned, '&');
  }

  /**
   * Convert encoding.
   *
   * @author Evasn <evans.yang@greenpacket.com.cn>
   *
   * @param string|array $data
   * @param string       $to
   * @param string       $from
   *
   * @return array
   */
  public static function encoding($data, $to = 'utf-8', $from = 'gb2312'): array
  {
    return Arr::encoding((array) $data, $to, $from);
  }

  /**
   * Get service config.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param null|string $key
   * @param null|mixed  $default
   *
   * @return mixed|null
   */
  public function getConfig($key = null, $default = null)
  {
    if (is_null($key)) {
      return $this->config->all();
    }

    if ($this->config->has($key)) {
      return $this->config[$key];
    }

    return $default;
  }



  /**
   * Get Base Uri.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @return string
   */
  public function getBaseUri()
  {
    return $this->baseUri;
  }

  /**
   * processingApiResult.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param $result
   *
   * @throws GatewayException
   * @throws InvalidConfigException
   * @throws InvalidSignException
   */
  protected static function processingApiResult($result): Collection
  {
    if (!isset($result['sign']) || '0' != $result['code']) {
      throw new GatewayException('API Error:'.$result['msg'].(isset($result['sub_code']) ? (' - '.$result['sub_code']) : ''), $result);
    }

    if (self::verifySign($result[$method], true, $result['sign'])) {
      return new Collection($result[$method]);
    }

    Events::dispatch(new Events\SignFailed('', $result));

    throw new InvalidSignException('The Response Sign Verify FAILED', $result);
  }

  /**
   * Set Http options.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @return self
   */
  protected function setHttpOptions(): self
  {
    if ($this->config->has('http') && is_array($this->config->get('http'))) {
      $this->config->forget('http.base_uri');
      $this->httpOptions = $this->config->get('http');
    }

    return $this;
  }

  /**
   * gets the current time millisecond timestamp.
   *
   * @author Evans Yang <evans.yang@greenpacket.com.cn>
   * @return float
   */
  public static function getMillisecond(): float
  {
    list($msec, $sec) = explode(' ', microtime());
    return (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
  }
  
  /**
   * buildUrlEncode
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   * @param string $method    get | post
   * @param string $endpoint  the request uri.
   * @param array $data       build data.
   * @return string
   */
  public static function buildUrlEncode(string $method, string $endpoint, array $data): String
  {
    $data = self::encoding($data, $data['charset'] ?? 'gb2312', 'utf-8');

    ksort($data);

    $endpoint .="?";
    foreach ($data as $k => $v) {
      if ($method == 'get' && $v !== '' && !is_null($v) && '@' != substr($v, 0, 1)) {
        $endpoint .= $k.'='.urlencode($v).'&';
      }else if ($method == 'post' && $v !== '' && !is_null($v) && $k != 'biz_content' && '@' != substr($v, 0, 1)) {
        $endpoint .= $k.'='.urlencode($v).'&';
      }
    }

    Logger::debug("Build The ".Str::studly($method)." Gateway Public Parameters Request Uri:", [$data, $endpoint]);

    return trim($endpoint, '&');
  }
}
