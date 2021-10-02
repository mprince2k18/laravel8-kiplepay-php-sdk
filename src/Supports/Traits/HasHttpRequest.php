<?php

namespace Greenpacket\KiplePay\Supports\Traits;

use Exception;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * Trait HasHttpRequest.
 *
 * @property string $baseUri
 * @property float  $timeout
 * @property float  $connectTimeout
 */
trait HasHttpRequest
{
  /**
   * Http client.
   *
   * @var Client|null
   */
  protected $httpClient = null;

  /**
   * Http client options.
   *
   * @var array
   */
  protected $httpOptions = [];

  /**
   * Send a GET request.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @return array|string
   */
  public function get(string $endpoint, array $query = [], array $headers = [])
  {
    return $this->request('get', $endpoint, [
      'headers' => $headers,
      'query' => $query,
    ]);
  }

  /**
   * Send a POST request.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param string|array $data
   *
   * @return array|string
   */
  public function post(string $endpoint, $data, string $type = '',array $options = [])
  {
    if ($type == 'file'){
      $options['multipart'] = $data;
    }else{
      if (!is_array($data)) {
        $options['body'] = $data;
      } else {
        $options['form_params'] = $data;
      }
    }

    return $this->request('post', $endpoint, $options);
  }

  /**
   * Send request.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @return array|string
   */
  public function request(string $method, string $endpoint, array $options = [])
  {
    return $this->unwrapResponse($this->getHttpClient()->{$method}($endpoint, $options));
  }

  /**
   * Set http client.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @return $this
   */
  public function setHttpClient(Client $client): self
  {
    $this->httpClient = $client;

    return $this;
  }

  /**
   * Return http client.
   */
  public function getHttpClient(): Client
  {
    if (is_null($this->httpClient)) {
      $this->httpClient = $this->getDefaultHttpClient();
    }

    return $this->httpClient;
  }

  /**
   * Get default http client.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   */
  public function getDefaultHttpClient(): Client
  {
    return new Client($this->getOptions());
  }

  /**
   * setBaseUri.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @return $this
   */
  public function setBaseUri(string $url): self
  {
    if (property_exists($this, 'baseUri')) {
      $parsedUrl = parse_url($url);

      $this->baseUri = ($parsedUrl['scheme'] ?? 'http').'://'.
          $parsedUrl['host'].(isset($parsedUrl['port']) ? (':'.$parsedUrl['port']) : '');
    }

    return $this;
  }


  /**
   * get client options debug
   *
   * @author Evans Yang <evans.yang@greenpacket.com.cn>
   * @return bool
   */
  public function getDebug()
  {
    return property_exists($this, 'debug') ? $this->debug : false ;
  }

  public function setDebug($debug): self
  {
    if (property_exists($this,'debug')){
      $this->debug = $debug;
    }

    return $this;
  }

  /**
   * getBaseUri.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   */
  public function getBaseUri(): string
  {
    return property_exists($this, 'baseUri') ? $this->baseUri : '';
  }

  public function getVerify()
  {
    return property_exists($this,'verify') ? $this->verify : false;
  }

  public function setVerify($verify): self
  {
    if(property_exists($this,'verify')){
      $this->verify = $verify;
    }

    return $this;
  }

  public function getTimeout(): float
  {
    return property_exists($this, 'timeout') ? $this->timeout : 5.0;
  }

  public function setTimeout(float $timeout): self
  {
    if (property_exists($this, 'timeout')) {
      $this->timeout = $timeout;
    }

    return $this;
  }

  public function getHeaders(): array
  {
    return property_exists($this, 'headers') ? $this->headers : [
      'Accept'            =>  'application/json',
      'User-Agent'        =>  'php/client',
      'Accept-Encoding'   =>  'gzip, deflate',
      'Accept-Language'   =>  'zh-CN,zh;q=0.9,en;q=0.8',
      'Cache-Control'     =>  'no-cache',
      'Content-Type'      =>  'application/json'
    ];
  }

  public function setHeaders(array $headers): self
  {
    if(property_exists($this,'headers')){
      $this->headers = $headers;
    }

    return $this;
  }

  public function getHttpErrors(): bool
  {
    return property_exists($this, 'httpErrors') ? $this->httpErrors : false;
  }

  public function setHttpErrors(bool $httpErrors): self
  {
    if(property_exists($this,'httpErrors')){
      $this->httpErrors= $httpErrors;
    }
    return $this;
  }

  public function getConnectTimeout(): float
  {
    return property_exists($this, 'connectTimeout') ? $this->connectTimeout : 3.14;
  }

  public function setConnectTimeout(float $connectTimeout): self
  {
    if (property_exists($this, 'connectTimeout')) {
      $this->connectTimeout = $connectTimeout;
    }

    return $this;
  }

  public function getAllowRedirects()
  {
    return property_exists($this,'allowRedirects') ? $this->allowRedirects : false;
  }

  public function setAllowRedirects($allowRedirects)
  {
    if (property_exists($this,'allowRedirects')) {
      $this->allowRedirects = $allowRedirects;
    }

    return $this;
  }

  /**
   * Get default options.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   */
  public function getOptions(): array
  {
    return array_merge(['base_uri' => $this->getBaseUri()],array_replace_recursive([
      'debug'             => $this->getDebug(),
      'verify'            => $this->getVerify(),
      'headers'           => $this->getHeaders(),
      'timeout'           => $this->getTimeout(),
      'http_errors'       => $this->getHttpErrors(),
      'connect_timeout'   => $this->getConnectTimeout(),
      'allow_redirects'   => $this->getAllowRedirects()
    ], $this->getHttpOptions()));
  }

  /**
   * setOptions.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @return $this
   */
  public function setOptions(array $options): self
  {
    return $this->setHttpOptions($options);
  }

  public function getHttpOptions(): array
  {
    return $this->httpOptions;
  }

  public function setHttpOptions(array $httpOptions): self
  {
    $this->httpOptions = $httpOptions;

    return $this;
  }

  /**
   * Convert response.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @return array|string
   */
  public function unwrapResponse(ResponseInterface $response)
  {
    $contentType = $response->getHeaderLine('Content-Type');
    $contents = $response->getBody()->getContents();

    if ($response->getStatusCode() != 200){
      throw new \Exception("Error Processing Request:$contents",1);
    }

    if (false !== stripos($contentType, 'json') || stripos($contentType, 'javascript')) {
      return json_decode($contents, true);
    } elseif (false !== stripos($contentType, 'xml')) {
      return json_decode(json_encode(simplexml_load_string($contents, 'SimpleXMLElement', LIBXML_NOCDATA), JSON_UNESCAPED_UNICODE), true);
    }

    return $contents;
  }
}
