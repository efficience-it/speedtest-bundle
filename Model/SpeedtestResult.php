<?php

namespace App\Model;

class SpeedtestResult
{
    private ?string $ip = null;

    private ?string $download = null;

    private ?string $upload = null;

    private ?string $ping = null;

    private ?string $jitter = null;

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getDownload(): ?string
    {
        return $this->download;
    }

    public function setDownload(?string $download): self
    {
        $this->download = $download;

        return $this;
    }

    public function getUpload(): ?string
    {
        return $this->upload;
    }

    public function setUpload(?string $upload): self
    {
        $this->upload = $upload;

        return $this;
    }

    public function getPing(): ?string
    {
        return $this->ping;
    }

    public function setPing(?string $ping): self
    {
        $this->ping = $ping;

        return $this;
    }

    public function getJitter(): ?string
    {
        return $this->jitter;
    }

    public function setJitter(?string $jitter): self
    {
        $this->jitter = $jitter;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'ip' => $this->ip,
            'download' => $this->download,
            'upload' => $this->upload,
            'ping' => $this->ping,
            'jitter' => $this->jitter
        ];
    }
}