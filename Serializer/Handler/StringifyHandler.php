<?php

namespace Clearcode\ElkBridgeBundle\Serializer\Handler;

use Carbon\Carbon;
use JMS\Serializer\JsonSerializationVisitor;

class StringifyHandler
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param JsonSerializationVisitor $visitor
     * @param Carbon                   $value
     *
     * @return []
     */
    public function toDateTime(JsonSerializationVisitor $visitor, Carbon $value)
    {
        return new \DateTime($value->toDateTimeString());
    }
}
