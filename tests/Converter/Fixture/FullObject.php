<?php

namespace tests\Clearcode\ElkBridgeBundle\Converter\Fixture;

class FullObject
{
    public function string()
    {
        return 'string';
    }

    public function int()
    {
        return 1;
    }

    public function bool()
    {
        return true;
    }

    public function float()
    {
        return 1.1;
    }

    public function emptyArray()
    {
        return [];
    }

    public function toArray()
    {
        return [];
    }

    public function null()
    {
        return;
    }

    public function __magic()
    {
        return 'something';
    }

    public function selfObject()
    {
        return new self();
    }

    public static function staticMethod()
    {
        return 'something';
    }

    public static function notGetter($data)
    {
        return $data;
    }
}
