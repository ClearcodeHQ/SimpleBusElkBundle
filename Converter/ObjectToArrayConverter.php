<?php

namespace Clearcode\ElkBridgeBundle\Converter;

interface ObjectToArrayConverter
{
    /**
     * @param object $event
     *
     * @return array
     */
    public function toArray($event);
}
