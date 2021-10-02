<?php

namespace Greenpacket\KiplePay\Contracts;

use Symfony\Component\HttpFoundation\Response;
use Greenpacket\KiplePay\Supports\Collection;

interface GatewayApplicationInterface
{
    /**
     * To gateway.
     *
     * @author Evans <evans.yang@greenpacket.com.cn>
     *
     * @param string $gateway
     * @param array  $params
     *
     * @return Collection|Response
     */
    public function gateway($gateway, $params);


    /**
     * Verify a request.
     *
     * @author Evans <evans.yang@greenpacket.com.cn>
     *
     * @param string|null $content
     * @param bool        $refund
     *
     * @return Collection
     */
    public function verify($content, bool $refund);
}
