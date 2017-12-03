<?php

namespace NotificationChannels\Unisender\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use NotificationChannels\Unisender\Exceptions\ApiRequestFailedException;
use NotificationChannels\Unisender\UnisenderApi;
use NotificationChannels\Unisender\UnisenderMessage;

class UnisenderApiTest extends TestCase
{
    public function testItShouldResetClientAfterUpdatingToken()
    {
        $api = new UnisenderApi('test');
        $firstClient = $api->getClient();

        $this->assertSame($firstClient, $api->getClient());

        $api->setToken('example');

        $this->assertNotSame($firstClient, $api->getClient());
    }

    public function testItShouldAttachDefaultFormParams()
    {
        $client = \Mockery::mock(Client::class);

        $client->shouldReceive('request')
            ->with('POST', 'sendSms', [
                'form_params' => [
                    'phone' => 'test',
                    'sender' => 'test',
                    'text' => 'test',
                    'format' => 'json',
                    'api_key' => 'example',
                ],
            ])
            ->andReturn(new Response());

        $api = \Mockery::mock(UnisenderApi::class.'[getClient]', ['example']);
        $api->shouldReceive('getClient')->andReturn($client);

        $api->sendSms((new UnisenderMessage())->from('test')->to('test')->content('test'));
    }

    public function testItShouldAttachDefaultQueryParamas()
    {
        $client = \Mockery::mock(Client::class);

        $client->shouldReceive('request')
            ->with('GET', 'sendSms', [
                'query' => [
                    'phone' => 'test',
                    'sender' => 'test',
                    'text' => 'test',
                    'format' => 'json',
                    'api_key' => 'example',
                ],
            ])
            ->andReturn(new Response());

        $api = \Mockery::mock(UnisenderApi::class.'[getClient]', ['example']);
        $api->shouldReceive('getClient')->andReturn($client);

        $api->performRequest('GET', 'sendSms', [
            'query' => [
                'phone' => 'test',
                'sender' => 'test',
                'text' => 'test',
            ],
        ]);
    }

    public function testItShouldThrowApiExceptionOnRequestError()
    {
        $api = \Mockery::mock(UnisenderApi::class.'[getClient]', ['example']);
        $api->shouldReceive('getClient')->andThrow(
            new RequestException('test', new Request('GET', '/'), new Response(404))
        );

        $this->expectException(ApiRequestFailedException::class);
        $api->performRequest('GET', '/');
    }
}
