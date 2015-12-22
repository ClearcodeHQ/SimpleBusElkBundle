<?php

namespace Clearcode\SimpleBusElkBundle;

use Clearcode\SimpleBusElkBundle\DependencyInjection\ElkBridgeExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SimpleBusElkBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new ElkBridgeExtension();
    }
}
