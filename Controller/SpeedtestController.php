<?php

namespace EfficienceIt\SpeedtestBundle\Controller;

use App\Model\SpeedtestResult;
use EfficienceIt\SpeedtestBundle\Service\ChunkService;
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

    private ChunkService $chunkService;
    private ClientIpService $clientIpService;
    private string $clientIP;

    public function __construct(ChunkService $chunkService, ClientIpService $clientIpService)
    {
        $this->chunkService = $chunkService;
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
    public function generateChunks() //chunk = segment of file, used to calculate download speed
    {
        return new Response(random_bytes(self::BYTES_SIZE * self::CHUNK_SIZE));
    }

    /**
     * @Route("/speedtest-results", name="speedtest_results", methods={"POST"})
     */
    public function speedtestResults(Request $request): Response
    {
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedException();
        }
        $requestContent = json_decode($request->getContent(), true);

        $result = (new SpeedtestResult())
            ->setIp($requestContent['clientIp'])
            ->setDownload($requestContent['dlStatus'])
            ->setUpload($requestContent['ulStatus'])
            ->setPing($requestContent['pingStatus'])
            ->setJitter($requestContent['jitterStatus'])
        ;

        return new JsonResponse($result->toArray());
    }
}