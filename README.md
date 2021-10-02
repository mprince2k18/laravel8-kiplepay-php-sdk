<h1 align="center"> KiplePay PHP SDK </h1>
<p align="center">The SDK mainly shows how to access the kiplepay gateway (only for WaaS customers).</p>

## Installing

```shell
$ composer require mprince/kiplepay
```
## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/m2c/kiplepay-php-sdk/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/m2c/kiplepay-php-sdk/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## Documents
[documents](http://sdks.kiplepay.com)

## Errors
If an error occurs while calling the associated gateway, That will thrown some errors  `GatewayException`,`InvalidSignException`, etc. We can review the details of the error by `$e->getMessage()`，at the same time, is also available through `$e->raw` Look at the raw data returned after calling the API, That raw data was an array.

## All Exceptions

* Greenpacket\KiplePay\Exceptions\InvalidGatewayException ，Means that the payment gateway supported by the SDK is used.
* Greenpacket\KiplePay\Exceptions\InvalidSignException ，Means that the Sign failed.
* Greenpacket\KiplePay\Exceptions\InvalidConfigException ，Means that the configuration parameters are missing.for example: `public_key`, `private_key`, etc.
* Greenpacket\KiplePay\Exceptions\GatewayException ，Represents the abnormal result of data returned by gateway server, for example: parameter error, non-existence of statement, etc.

## Usage

```php
<?php
  use Greenpacket\KiplePay\Kiple;

  $config = [
    'app_id' => '2016082000295641',
    'format' => 'JSON',     //optional default json
    'charset' => 'UTF-8',   //optional default utf8
    'sign_type' => 'RSA2',  //optional default rsa2
    'version'=> '1.0.0',    //optional default 1.0.0
    'endpoint' => 'http://47.254.245.66/',
    'notify_url'=>'https://www.kiplepay.com',   //optional default null
    'return_url'=> 'https://www.kiplepark.com', //optional default null
    'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAyzUiFx6ge0KXgF2OgZNjcUHWdopSn4dXde16cLEIKyXXdyYxDj/e/Wie0rsA0wJ6SusTFFk/ba+qXutX3i2DTau3jlPYTT/q6oiktDhOjmt3lSQGyxPihOj3gtjvR5n2Ri8DNU9VXdhqhMzJ0QzKh41XLN+Cuy6Xa0WXx4EWE24MGlGV+1dr6vjO56eF8I/+Cy1MwrBfldlq9GO5lRF5wRuVPPM9WyRfUmoyrnIRcFJsoKfdlZxJJu3zsuUtqEuZv5blmRFF+/XtgTnEP7kV6oZq6TIFHw8Z2ZPx8Y2KDFV5reBTuXAsglUfoRvNtA4gLEMgV9kEYlaugT7wDPsheQIDAQAB', // 
    'private_key'=>'MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDLNSIXHqB7QpeAXY6Bk2NxQdZ2ilKfh1d17XpwsQgrJdd3JjEOP979aJ7SuwDTAnpK6xMUWT9tr6pe61feLYNNq7eOU9hNP+rqiKS0OE6Oa3eVJAbLE+KE6PeC2O9HmfZGLwM1T1Vd2GqEzMnRDMqHjVcs34K7LpdrRZfHgRYTbgwaUZX7V2vq+M7np4Xwj/4LLUzCsF+V2Wr0Y7mVEXnBG5U88z1bJF9SajKuchFwUmygp92VnEkm7fOy5S2oS5m/luWZEUX79e2BOcQ/uRXqhmrpMgUfDxnZk/HxjYoMVXmt4FO5cCyCVR+hG820DiAsQyBX2QRiVq6BPvAM+yF5AgMBAAECggEBAIc7Xup/6VDeKjC1EkdNLNqMdAsDVqTvztaeKeOhDMyaLGAQvIi9HMsLutaGuK+0cGo3MsVR95IpW3o7qIglRcKEym0fg001gkJ+mQ2Og0joX/aSuSqgmxjUaRNdBzBhV7GxS7NIjokboxepqY1Ds+yttRwZysiC4yXydGXClExsukjlaXyVoGZIRUQUXaXs4XUC6sXwv645KbM15kzgFWOiL0+1yZMt0F6tb2kU8Z2rQTyP5yaHEUGbgzY7z3S0rI2xf9CtuQg9+wP8Ofj2597gUv5Au5HiQwiQExe4EfosIg2ZxqLClj3uOw3dOx1lLxfgEvXWVWd0tm9y6QVI32ECgYEA659aClIDKMqvqz2C+iGzDAOr6a2W2n2534duKGu5TeDh94ql6S8nD2lOfSBP5UDFt6bsmlASj/nSFz6p7pdzPG9OGJjMWov9yxsDZWMRGJ+D3WDd3s+ZvMLZRfzaox3GNsW3cRsPJ9vYfTQio/hdpQ9K0+fzaGpkzP3sEOvZQK0CgYEA3MgeqKhcKDJTPODxt2IAP9CcKm9dyVRRUeaOyroKmKGBFGHGga37n9RFpEKWtHzcIUuqXBwMwl05UnWfVn6YeLkLyFPnLsNECJuthnOngNJsFyNHKr6GgmpCiIEVOI3cqFYfihDLlQSQL7tO7MpfoQ9tdtERUb3qo2wwdfeZYX0CgYBcbjWXKNb+cIx7I3U3BHNFekc9MxCjg4Cf9HO9PY0CxP4/6k7ta8bp38ifg0Z0S3WEduIIIvM1Ma492iI1a4oUIiHDumUn/BTCUUWCx1sUirbi6DYBSvUnPFSZhPiL1olEQUmWACRw4WhKrWINasfpkVcsS6iLxHjohY/Oj4a5PQKBgQDcSVg90+5PtRbUUWUcIk45Xf3TYVbkgJq66x5iLApSjCJsobocvemoaXYrFL2lzEcfeY27ZcldTQLawb1/4cRj/84/zWeHgxEovZv/4PmqUUnENFDX104CZd+Ir7LqwLD/zR6e9W8Leoga9/shzDJqUyhXOvba5nFtKY+YxLlnSQKBgQCzpHeK/YKUvin7wCdheJv8wHv1UStn/bp3Ys4ID/aqVVis9ihLCkb4n35/uY0s5FYAhyy2NURWJtZg8LvHO5K4jHjdg4yXkp5vLWilsRIybhwd027vk1+5OPoNCjgJnBx0LArkg6p3tEhlUZLwGfl/tX2ESvS7ImWt6GElp7tqIA==',
    'log' => [ // optional
      'file' => './logs/kiple.log',
      'level' => 'debug', // It is recommended to set the production environment level to INFO and the development environment to Debug
      'type' => 'daily', // optional, [single,daily].
      'max_file' => 30, // optional, Valid when type is daily, default is 30 days
    ],
    'http'=>[ // optional
      'timeout' => 5.0,         // optional, default the timeout 3.14
      'connect_timeout' => 5.0, // optional, default the timeout 3.14
      'headers' =>[
        'Accept'            =>  'application/json', // optional,
        'User-Agent'        =>  'php/client', // optional,
        'Accept-Encoding'   =>  'gzip, deflate',  // optional,
        'Accept-Language'   =>  'zh-CN,zh;q=0.9,en;q=0.8', // optional,
        'Cache-Control'     =>  'no-cache', // optional,
        'Content-Type'      =>  'application/json', // optional,
        # the other custom parameters
        'token'             => '', // 
        ....
      ]
    ]
  ];

  # Get gateway request.
  try{
    $response = Kiple::gateway($config)->get([
      'api_url'=> '/user/api/v1.0/users/check-pin'
      'biz_content'=> [
        'pin'=>'2',
        'user_id'=>29,
      ]
    ]);
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  }catch(\Exception $e){
    die($e->getMessage());
  }

  # Post gateway request.
  try{
    $response = Kiple::gateway($config)->post([
      'api_url'=> '/user/api/v1.0/users/check-pin'
      'biz_content'=> [
        'pin'=>'2',
        'user_id'=>29,
      ]
    ]);
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  }catch(\Exception $e){
    die($e->getMessage());
  }

  #File gateway request.
    try{
    $response = Kiple::gateway($config)->files([
      'api_url'=> '/user/api/v1.0/users/check-pin'
      'biz_content'=> [
        'pin'=>'2',
        'user_id'=>29,
      ],
      'file_content'=>[
        'file_one'=>'./files/test_1.jpeg',
        'file_two'=>'./files/test_2.jpeg',
      ]
    ]);
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  }catch(\Exception $e){
    die($e->getMessage());
  }

  # Verify gateway.
  try {
    $response = Kiple::gateway($config)->verify();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  } catch (\Exception $e) {
    die($e->getMessage());
  }

```

## License

MIT