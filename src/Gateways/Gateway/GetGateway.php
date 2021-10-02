<?php

namespace Greenpacket\KiplePay\Gateways\Gateway;

use Greenpacket\KiplePay\Events;
use Greenpacket\KiplePay\Supports\Collection;
use Symfony\Component\HttpFoundation\Response;
use Greenpacket\KiplePay\Contracts\GatewayInterface;
use Greenpacket\KiplePay\Exceptions\BusinessException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Greenpacket\KiplePay\Exceptions\InvalidConfigException;

class GetGateway extends KipleGateway
{
  /**
   * Pay an order.
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

    if(isset($biz_array['api_url']) && $biz_array['api_url']){
      $endpoint = $endpoint.$biz_array['api_url'];
      unset($biz_array['api_url']);
    }else{
      throw new BusinessException("Missing the request api url");
    }

    $payload['biz_content'] = json_encode($biz_array['biz_content']);

    $payload['sign'] = Support::generateSign($payload);

    Events::dispatch(new Events\RequestStarted('Get', $endpoint, $payload));

    $endpoint = Support::buildUrlEncode("get",$endpoint,$payload);
    
    return Support::requestApi('get',$endpoint,$payload);
  }
}
