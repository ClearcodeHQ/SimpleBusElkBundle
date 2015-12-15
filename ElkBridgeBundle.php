<?php

namespace Clearcode\ElkBridgeBundle;

use Clearcode\ElkBridgeBundle\DependencyInjection\ElkBridgeExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ElkBridgeBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new ElkBridgeExtension();
    }
}
