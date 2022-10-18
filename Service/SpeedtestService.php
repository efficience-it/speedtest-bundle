<?php

namespace EfficienceIt\SpeedtestBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SpeedtestService
{
    public function displaySpeedtest(): string
    {
        //@speedtest allows you to go directly to the views folder
        return '@speedtest/speedtest.html.twig';
    }
}