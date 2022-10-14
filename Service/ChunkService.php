<?php

namespace EfficienceIt\SpeedtestBundle\Service;

class ChunkService
{
// How many chunks will the speedtest generate ?
    public function getChunkCount(int $ckSize): int
    {
        if ($ckSize <= 0) return 4;
        if ($ckSize > 1024) return 1024;
        return $ckSize;
    }
}