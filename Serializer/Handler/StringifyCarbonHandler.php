<?php

namespace Clearcode\ElkBridgeBundle\Serializer\Handler;

use Carbon\Carbon;
use JMS\Serializer\JsonSerializationVisitor;

class StringifyCarbonHandler
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param JsonSerializationVisitor $visitor
     * @param Carbon                   $value
     *
     * @return string
     */
    public function toDateTime(JsonSerializationVisitor $visitor, Carbon $value)
    {
        return $value->toDateTimeString();
    }
}
