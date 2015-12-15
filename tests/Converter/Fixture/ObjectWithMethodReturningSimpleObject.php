<?php

namespace tests\Clearcode\ElkBridgeBundle\Converter\Fixture;

class ObjectWithMethodReturningSimpleObject
{
    public function simpleObject()
    {
        return new SimpleObject();
    }
}
