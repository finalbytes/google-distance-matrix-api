<?php

namespace FinalBytes\GoogleDistanceMatrix\Response;

use FinalBytes\GoogleDistanceMatrix\Exception\Exception;

class Element
{
    const STATUS_OK = 'OK';
    const STATUS_NOT_FOUND = 'NOT_FOUND';
    const STATUS_ZERO_RESULTS = 'ZERO_RESULTS';
    const STATUS = [
        self::STATUS_OK,
        self::STATUS_NOT_FOUND,
        self::STATUS_ZERO_RESULTS,
    ];

    private $status;

    private $duration;

    private $distance;

    public function __construct($status, Duration $duration, Distance $distance)
    {
        if (!in_array($status, self::STATUS)) {
            throw new Exception(sprintf('Unknown status code: %s', $status));
        }
        $this->status = $status;
        $this->duration = $duration;
        $this->distance = $distance;
    }

    public function getStatus() : string
    {
        return $this->status;
    }

    public function getDuration() : Duration
    {
        return $this->duration;
    }

    public function getDistance() : Distance
    {
        return $this->distance;
    }
}