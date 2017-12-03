<?php

namespace NotificationChannels\Unisender;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Str;
use NotificationChannels\Unisender\Exceptions\ApiRequestFailedException;

class UnisenderApi
{
    const BASE_URI = 'https://api.unisender.com/ru/api/';

    protected $token;
    protected $client;

    public function __construct(string $token = null)
    {
        $this->token = $token;
        $this->client = null;
    }

    public function setToken(string $token)
    {
        $this->token = $token;
        $this->client = null;
    }

    public function getClient()
    {
        if (!is_null($this->client)) {
            return $this->client;
        }

        return $this->client = new Client([
            'base_uri' => static::BASE_URI,
        ]);
    }

    public function performRequest(string $method, string $url, array $options = [])
    {
        $response = null;
        $method = Str::upper($method);
        $options = $this->withDefaultOptions($method, $options);

        try {
            $response = $this->getClient()->request($method, $url, $options);
        } catch (RequestException $e) {
            throw new ApiRequestFailedException($e, $e->getResponse());
        }

        return json_decode((string) $response->getBody(), true);
    }

    protected function withDefaultOptions(string $method, array $options = [])
    {
        $parametersBag = '';

        switch ($method) {
            case 'GET':
                $parametersBag = 'query';
                break;
            case 'POST':
                $parametersBag = 'form_params';
                break;
        }

        if (!isset($options[$parametersBag])) {
            $options[$parametersBag] = [];
        }

        $options[$parametersBag]['format'] = 'json';
        $options[$parametersBag]['api_key'] = $this->token;

        return $options;
    }

    public function sendSms(UnisenderMessage $message)
    {
        return $this->performRequest('POST', 'sendSms', [
            'form_params' => [
                'phone' => $message->to,
                'sender' => $message->from,
                'text' => $message->content,
            ]
        ]);
    }
}
