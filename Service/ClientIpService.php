<?php

namespace EfficienceIt\SpeedtestBundle\Service;

use Symfony\Component\HttpFoundation\Request;

class ClientIpService
{
    public function getClientIp(): string
    {
        $request = Request::createFromGlobals();

        $httpClientIp = $request->server->get('HTTP_CLIENT_IP');
        $httpRealIp = $request->server->get('HTTP_X_REAL_IP');
        $httpForwardedFor = $request->server->get('HTTP_X_FORWARDED_FOR');
        $remoteAddr = $request->server->get('REMOTE_ADDR');

        if (!empty($httpClientIp)) {
            $ip = $httpClientIp;
        } elseif (!empty($httpRealIp)) {
            $ip = $httpRealIp;
        } elseif (!empty($httpForwardedFor)) {
            $ip = $httpForwardedFor;
            $ip = preg_replace('/,.*/', '', $ip); # hosts are comma-separated, client is first
        } else {
            $ip = $remoteAddr;
        }

        return preg_replace('/^::ffff:/', '', $ip);
    }

    public function getLocalOrPrivateIpInfo(string $ip): ?string
    {
        // ::1/128 is the only localhost ipv6 address. there are no others, no need to strpos this
        if ('::1' === $ip) return 'localhost IPv6 access';

        // simplified IPv6 link-local address (should match fe80::/10)
        if (stripos($ip, 'fe80:') === 0) return 'link-local IPv6 access';

        // anything within the 127/8 range is localhost ipv4, the ip must start with 127.0
        if (strpos($ip, '127.') === 0) return 'localhost IPv4 access';

        // 10/8 private IPv4
        if (strpos($ip, '10.') === 0) return 'private IPv4 access';

        // 172.16/12 private IPv4
        if (preg_match('/^172\.(1[6-9]|2\d|3[01])\./', $ip) === 1) return 'private IPv4 access';

        // 192.168/16 private IPv4
        if (strpos($ip, '192.168.') === 0) return 'private IPv4 access';

        // IPv4 link-local
        if (strpos($ip, '169.254.') === 0) return 'link-local IPv4 access';

        return null;
    }
}