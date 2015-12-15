<?php

namespace tests\Clearcode\ElkBridgeBundle\Converter\Fixture;

class ObjectWithToStringMagicMethod
{
    public function __toString()
    {
        return 'Hello World!';
    }
}
