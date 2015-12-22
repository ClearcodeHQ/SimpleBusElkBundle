<?php

namespace Clearcode\SimpleBusElkBundle\Converter;

interface ObjectToArrayConverterInterface
{
    /**
     * @param object $object The object to convert
     *
     * @return array
     */
    public function toArray($object);
}
