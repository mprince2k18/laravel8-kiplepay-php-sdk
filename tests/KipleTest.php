<?php

namespace Greenpacket\KiplePay\Tests;

use Greenpacket\KiplePay\Kiple;
use Greenpacket\KiplePay\Logger;
use Greenpacket\KiplePay\Tests\TestCase;
use Greenpacket\KiplePay\Supports\Collection;
use Symfony\Component\HttpFoundation\Response;



class KipleTest extends TestCase
{
  public function testGetGateway(){
    $logger = new Logger();
    $this->config['http']['headers']['token'] = $this->token;
    $response = Kiple::gateway($this->config)->get([
      'api_url'=>'/user/api/v1.0/users/profile',
      'biz_content'=>$this->device
    ]);

    $this->assertInstanceOf(Collection::class, $response);
    $logger->info($response);
    $this->assertEquals('success', $response["msg"]);
  }

  public function testPostGateway()
  {
    $logger = new Logger();
    $this->config['http']['headers']['token'] = $this->token;
    $response = Kiple::gateway($this->config)->post([
      'api_url'=>'/user/api/v1.0/users/reset-pin',
      'biz_content'=>array_merge($this->device,[
        'otp'=>'123456'
      ])
    ]);

    $this->assertInstanceOf(Collection::class, $response);
    $logger->info($response);
    $this->assertEquals('success', $response["msg"]);
  }

  public function testFilesGateway(){
    $logger = new Logger();
    $this->config['http']['headers']['token'] = $this->token;
    $response = Kiple::gateway($this->config)->files([
      'api_url'=>'/user/api/v1.0/ekyc/nric',
      'biz_content'=>$this->device,
      'file_content'=>[
        "nric_front_image"=>__DIR__."/files/b-1.jpeg",
        "nric_back_image"=>__DIR__."/files/b-2.jpeg",
      ]
    ]);

    $this->assertInstanceOf(Collection::class, $response);
    $logger->info($response);
    $this->assertEquals('success', $response["msg"]);
  }
}
