<?php

namespace Greenpacket\KiplePay\Exceptions;

class BusinessException extends GatewayException
{
    /**
     * Bootstrap.
     *
     * @author Evans <evans.yang@greenpacket.com.cn>
     *
     * @param string       $message
     * @param array|string $raw
     */
    public function __construct($message, $raw = [])
    {
        parent::__construct('ERROR_BUSINESS: '.$message, $raw, self::ERROR_BUSINESS);
    }
}
