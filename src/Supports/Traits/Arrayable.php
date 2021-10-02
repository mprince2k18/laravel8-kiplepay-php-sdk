<?php

declare(strict_types=1);

namespace Greenpacket\KiplePay\Supports\Traits;

use ReflectionClass;
use Greenpacket\KiplePay\Supports\Str;

trait Arrayable
{
    /**
     * toArray.
     *
     * @author Evans <evans.yang@greenpacket.com.cn>
     *
     * @throws \ReflectionException
     */
    public function toArray(): array
    {
        $result = [];

        foreach ((new ReflectionClass($this))->getProperties() as $item) {
            $k = $item->getName();
            $method = 'get'.Str::studly($k);

            $result[Str::snake($k)] = method_exists($this, $method) ? $this->{$method}() : $this->{$k};
        }

        return $result;
    }
}
