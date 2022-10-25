<?php

namespace EfficienceIt\SpeedtestBundle\Controller;

use EfficienceIt\SpeedtestBundle\Service\ClientIpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpeedtestController extends AbstractController
{
    private const CHUNK_SIZE = 50;
    private const BYTES_SIZE = 1048576;

    private ClientIpService $clientIpService;
    private string $clientIP;

    public function __construct(ClientIpService $clientIpService)
    {
        $this->clientIpService = $clientIpService;
        $this->clientIP = $this->clientIpService->getClientIp();
    }

    /**
     * @Route("/get-ip/{random_number<\d*\.?\d*>}", name="speedtest_get_ip", methods={"GET"})
     */
    public function getIp(): Response
    {
        $localIpInfo = $this->clientIpService->getLocalOrPrivateIpInfo($this->clientIP);

        $processedString = $this->clientIP;
        if (is_string($localIpInfo)) {
            $processedString .= ' - '.$localIpInfo;
        }

        return new JsonResponse([
            'processedString' => $processedString
        ]);
    }

    /**
     * @Route("/generate-chunks/{random_number<\d*\.?\d*>}", name="speedtest_generate_chunks", methods={"GET"})
     */
    public function generateChunks(): Response //chunk = segment of file, used to calculate download speed
    {
        return new Response(random_bytes(self::BYTES_SIZE * self::CHUNK_SIZE));
    }
}