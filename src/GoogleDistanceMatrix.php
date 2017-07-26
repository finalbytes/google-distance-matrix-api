<?php

namespace FinalBytes\GoogleDistanceMatrix;

use GuzzleHttp\Client;

class GoogleDistanceMatrix
{
    const URL = 'https://maps.googleapis.com/maps/api/distancematrix/json';

    const AVOID_TOLLS = 'tolls';
    const AVOID_HIGHWAYS = 'highways';
    const AVOID_FERRIES = 'ferries';
    const AVOID_INDOOR = 'indoor';

    const MODE_BICYCLING = 'bicycling';
    const MODE_DRIVING = 'driving';
    const MODE_TRANSIT = 'transit';
    const MODE_WALKING = 'walking';

    const UNITS_IMPERIAL = 'imperial';
    const UNITS_METRIC = 'metric';

    const LANGUAGE = 'en-US';

    private $apiKey;

    private $avoid;

    private $destinations;

    private $language;

    private $mode;

    private $origins;

    private $units;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getApiKey() : string
    {
        return $this->apiKey;
    }

    public function getLanguage() : string
    {
        return $this->language;
    }

    public function setLanguage($language = self::LANGUAGE) : GoogleDistanceMatrix
    {
        $this->language = $language;
        return $this;
    }

    public function getUnits() : string
    {
        return $this->units;
    }

    public function setUnits($units = self::UNITS_METRIC) : GoogleDistanceMatrix
    {
        $this->units = $units;
        return $this;
    }

    public function getOrigins() : array
    {
        return $this->origins;
    }

    public function addOrigin($origin) : GoogleDistanceMatrix
    {
        $this->origins[] = $origin;
        return $this;
    }

    public function getDestinations() : array
    {
        return $this->destinations;
    }

    public function addDestination($destination) : GoogleDistanceMatrix
    {
        $this->destinations[] = $destination;
        return $this;
    }

    public function getMode() : string
    {
        return $this->mode;
    }

    public function setMode($mode = self::MODE_DRIVING) : GoogleDistanceMatrix
    {
        $this->mode = $mode;
        return $this;
    }

    public function getAvoid() : string
    {
        return $this->avoid;
    }

    public function setAvoid($avoid) : GoogleDistanceMatrix
    {
        $this->avoid = $avoid;
        return $this;
    }

    public function sendRequest() : GoogleDistanceMatrixResponse
    {
        $this->validateRequest();
        $data = [
            'key' => $this->getApiKey(),
            'language' => $this->getLanguage(),
            'origins' => count($this->origins) > 1 ? implode('|', $this->origins) : $this->origins[0],
            'destinations' => count($this->destinations) > 1 ? implode('|', $this->destinations) : $this->destinations[0],
            'mode' => $this->getMode(),
            'avoid' => $this->getAvoid(),
            'units' => $this->getUnits()
        ];
        $parameters = http_build_query($data);
        $url = self::URL.'?'.$parameters;

        return $this->request('GET', $url);
    }

    private function validateRequest() : void
    {
        if (empty($this->getOrigins())) {
            throw new Exception\OriginException('The origin must be set.');
        }
        if (empty($this->getDestinations())) {
            throw new Exception\DestinationException('The destination must be set.');
        }
    }

    private function request($type = 'GET', $url) : GoogleDistanceMatrixResponse
    {
        $client = new Client();
        $response = $client->request($type, $url);
        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Response with status code '.$response->getStatusCode());
        }
        $responseObject = new GoogleDistanceMatrixResponse(json_decode($response->getBody()->getContents()));
        $this->validateResponse($responseObject);
        return $responseObject;
    }

    private function validateResponse(GoogleDistanceMatrixResponse $response) : void
    {
        switch ($response->getStatus()) {
            case GoogleDistanceMatrixResponse::RESPONSE_STATUS_OK:
                break;
            case GoogleDistanceMatrixResponse::RESPONSE_STATUS_INVALID_REQUEST:
                throw new Exception\ResponseException("Invalid request.", 1);
                break;
            case GoogleDistanceMatrixResponse::RESPONSE_STATUS_MAX_ELEMENTS_EXCEEDED:
                throw new Exception\ResponseException("The product of the origin and destination exceeds the limit per request.", 2);
                break;
            case GoogleDistanceMatrixResponse::RESPONSE_STATUS_OVER_QUERY_LIMIT:
                throw new Exception\ResponseException("The service has received too many requests from your application in the allowed time range.", 3);
                break;
            case GoogleDistanceMatrixResponse::RESPONSE_STATUS_REQUEST_DENIED:
                throw new Exception\ResponseException("The service denied the use of the Distance Matrix API service by your application.", 4);
                break;
            case GoogleDistanceMatrixResponse::RESPONSE_STATUS_UNKNOWN_ERROR:
                throw new Exception\ResponseException("Unknown error.", 5);
                break;
            default:
                throw new Exception\ResponseException(sprintf("Unknown status code: %s",$response->getStatus()), 6);
                break;
        }
    }
}