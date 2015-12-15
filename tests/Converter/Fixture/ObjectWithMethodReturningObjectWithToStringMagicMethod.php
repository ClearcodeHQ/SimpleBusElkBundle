<?php

namespace tests\Clearcode\ElkBridgeBundle\Converter\Fixture;

class ObjectWithMethodReturningObjectWithToStringMagicMethod
{
    public function objectWithToStringMagicMethod()
    {
        return new ObjectWithToStringMagicMethod();
    }
}
