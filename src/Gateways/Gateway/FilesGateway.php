<?php

namespace Greenpacket\KiplePay\Gateways\Gateway;

use Greenpacket\KiplePay\Events;
use Greenpacket\KiplePay\Supports\Collection;
use Symfony\Component\HttpFoundation\Response;
use Greenpacket\KiplePay\Contracts\GatewayInterface;
use Greenpacket\KiplePay\Exceptions\BusinessException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Greenpacket\KiplePay\Exceptions\InvalidConfigException;

class FilesGateway extends KipleGateway
{
  /**
   * the files gateway.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param string $endpoint
   * @param array  $payload
   *
   * @throws InvalidConfigException
   *
   * @return Collection
   */
  public function gateway($endpoint, array $payload): Collection
  {

    $biz_array = json_decode($payload['biz_content'], true);

    if(isset($biz_array['file_content'])){
      $files = static::buildMultipart($biz_array['file_content'],'file');
      unset($biz_array['file_content']);
    }else{
      throw new BusinessException("Missing the files parameters");
    }

    if(isset($biz_array['api_url']) && $biz_array['api_url']){
      $endpoint = $endpoint.$biz_array['api_url'];
      unset($biz_array['api_url']);
    }else{
      throw new BusinessException("Missing the request api url");
    }

    if(isset($biz_array['biz_content'])){
      $business = static::buildMultipart($biz_array['biz_content']);
    }else{
      throw new BusinessException("Missing the business parameters");
    }
    
    $payload['biz_content'] = json_encode($biz_array['biz_content']);

    $payload['sign'] = Support::generateSign($payload);
    
    
    Events::dispatch(new Events\RequestStarted('Post', $endpoint, $payload));
    
    $endpoint = Support::buildUrlEncode("post",$endpoint,$payload);

    $multipart = array_merge($files,$business,[
      [
        'name'=>'biz_content',
        'contents'=>'biz_content='.urlencode($payload['biz_content']),
      ]
    ]);

    return Support::requestApi('files',$endpoint,$multipart);
  }

  /**
   * build the file upload multipart parameters
   *
   * @author Evans Yang <evans.yang@greenpacket.com.cn>
   * @param array $datas
   * @param string $type
   * @return array
   */
  private static function buildMultipart(array $datas,string $type="normal"): array{
    $multipart = [];
    $index = 0;

    foreach($datas AS $key=>$val){
      $multipart[$index]['name']=$key;
      $multipart[$index]['contents']= ($type == 'file')?fopen($val,'r'):$val;
      // $multipart[$index]['headers']= ['Content-Type'=>"multipart/form-data"];
      $index++;
    }

    return $multipart;
  }
}
