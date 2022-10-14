<?php

namespace EfficienceIt\SpeedtestBundle;

use EfficienceIt\SpeedtestBundle\DependencyInjection\SpeedtestExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SpeedtestBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $ext = new SpeedtestExtension([], $container);

    }
}