<?php

namespace Greenpacket\KiplePay\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{

  protected $token = 'da8079a33df4430794085a442e3f8a6c';

  protected $device = array(
    'device_id'   => "9856654",
    'ip_address'  => "222.212.90.238",
    'device_model'=> "098765",
    'latitude'    => "30.53932",
    'longitude'   => "104.06208",
  );

  protected $config = [
    'app_id' => '2016082000295641',
    'endpoint' => 'http://47.254.245.66',
    'notify_url'=>'https://www.kiplepay.com',
    'return_url'=> 'https://www.kiplepark.com',
    'private_key'=>'MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDLNSIXHqB7QpeAXY6Bk2NxQdZ2ilKfh1d17XpwsQgrJdd3JjEOP979aJ7SuwDTAnpK6xMUWT9tr6pe61feLYNNq7eOU9hNP+rqiKS0OE6Oa3eVJAbLE+KE6PeC2O9HmfZGLwM1T1Vd2GqEzMnRDMqHjVcs34K7LpdrRZfHgRYTbgwaUZX7V2vq+M7np4Xwj/4LLUzCsF+V2Wr0Y7mVEXnBG5U88z1bJF9SajKuchFwUmygp92VnEkm7fOy5S2oS5m/luWZEUX79e2BOcQ/uRXqhmrpMgUfDxnZk/HxjYoMVXmt4FO5cCyCVR+hG820DiAsQyBX2QRiVq6BPvAM+yF5AgMBAAECggEBAIc7Xup/6VDeKjC1EkdNLNqMdAsDVqTvztaeKeOhDMyaLGAQvIi9HMsLutaGuK+0cGo3MsVR95IpW3o7qIglRcKEym0fg001gkJ+mQ2Og0joX/aSuSqgmxjUaRNdBzBhV7GxS7NIjokboxepqY1Ds+yttRwZysiC4yXydGXClExsukjlaXyVoGZIRUQUXaXs4XUC6sXwv645KbM15kzgFWOiL0+1yZMt0F6tb2kU8Z2rQTyP5yaHEUGbgzY7z3S0rI2xf9CtuQg9+wP8Ofj2597gUv5Au5HiQwiQExe4EfosIg2ZxqLClj3uOw3dOx1lLxfgEvXWVWd0tm9y6QVI32ECgYEA659aClIDKMqvqz2C+iGzDAOr6a2W2n2534duKGu5TeDh94ql6S8nD2lOfSBP5UDFt6bsmlASj/nSFz6p7pdzPG9OGJjMWov9yxsDZWMRGJ+D3WDd3s+ZvMLZRfzaox3GNsW3cRsPJ9vYfTQio/hdpQ9K0+fzaGpkzP3sEOvZQK0CgYEA3MgeqKhcKDJTPODxt2IAP9CcKm9dyVRRUeaOyroKmKGBFGHGga37n9RFpEKWtHzcIUuqXBwMwl05UnWfVn6YeLkLyFPnLsNECJuthnOngNJsFyNHKr6GgmpCiIEVOI3cqFYfihDLlQSQL7tO7MpfoQ9tdtERUb3qo2wwdfeZYX0CgYBcbjWXKNb+cIx7I3U3BHNFekc9MxCjg4Cf9HO9PY0CxP4/6k7ta8bp38ifg0Z0S3WEduIIIvM1Ma492iI1a4oUIiHDumUn/BTCUUWCx1sUirbi6DYBSvUnPFSZhPiL1olEQUmWACRw4WhKrWINasfpkVcsS6iLxHjohY/Oj4a5PQKBgQDcSVg90+5PtRbUUWUcIk45Xf3TYVbkgJq66x5iLApSjCJsobocvemoaXYrFL2lzEcfeY27ZcldTQLawb1/4cRj/84/zWeHgxEovZv/4PmqUUnENFDX104CZd+Ir7LqwLD/zR6e9W8Leoga9/shzDJqUyhXOvba5nFtKY+YxLlnSQKBgQCzpHeK/YKUvin7wCdheJv8wHv1UStn/bp3Ys4ID/aqVVis9ihLCkb4n35/uY0s5FYAhyy2NURWJtZg8LvHO5K4jHjdg4yXkp5vLWilsRIybhwd027vk1+5OPoNCjgJnBx0LArkg6p3tEhlUZLwGfl/tX2ESvS7ImWt6GElp7tqIA==',
    'public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAyzUiFx6ge0KXgF2OgZNjcUHWdopSn4dXde16cLEIKyXXdyYxDj/e/Wie0rsA0wJ6SusTFFk/ba+qXutX3i2DTau3jlPYTT/q6oiktDhOjmt3lSQGyxPihOj3gtjvR5n2Ri8DNU9VXdhqhMzJ0QzKh41XLN+Cuy6Xa0WXx4EWE24MGlGV+1dr6vjO56eF8I/+Cy1MwrBfldlq9GO5lRF5wRuVPPM9WyRfUmoyrnIRcFJsoKfdlZxJJu3zsuUtqEuZv5blmRFF+/XtgTnEP7kV6oZq6TIFHw8Z2ZPx8Y2KDFV5reBTuXAsglUfoRvNtA4gLEMgV9kEYlaugT7wDPsheQIDAQAB',
    'log' => [ // optional
      'file' => './tests/logs/kiple.log',
      'level' => 'debug', // It is recommended to set the production environment level to INFO and the development environment to Debug
      'type' => 'daily', // optional, [single,daily].
      'max_file' => 30, // optional, Valid when type is daily, default is 30 days
    ],
    'http'=>[
      'timeout' => 50.0,
      'connect_timeout' => 50.0,
      "headers"=>[]
    ]
  ];

  public function setUp()
  {
    
  }

  public function tearDown()
  {

  }
}
