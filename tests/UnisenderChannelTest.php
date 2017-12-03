<?php

namespace NotificationChannels\Unisender\Tests;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use NotificationChannels\Unisender\Tests\Fakes\FakeNotifiable;
use NotificationChannels\Unisender\Tests\Fakes\FakeNotification;
use NotificationChannels\Unisender\UnisenderApi;
use NotificationChannels\Unisender\UnisenderChannel;
use NotificationChannels\Unisender\UnisenderMessage;

class UnisenderChannelTest extends TestCase
{
    public function testItShouldSendNotifications()
    {
        $api = \Mockery::mock(UnisenderApi::class);
        $notifiable = new FakeNotifiable();
        $notification = new FakeNotification();

        $api->shouldReceive('sendSms')->once();

        $channel = new UnisenderChannel($api);
        $channel->send($notifiable, $notification);
    }

    public function testItShouldThrowExceptions()
    {
        $api = \Mockery::mock(UnisenderApi::class);
        $api->shouldReceive('sendSms')->andThrow(\InvalidArgumentException::class);

        $notifiable = new FakeNotifiable();
        $notification = new FakeNotification();

        $this->expectException(\InvalidArgumentException::class);

        (new UnisenderChannel($api))->send($notifiable, $notification);
    }

    public function testItShouldNotThrowExceptionsIfSilentModeIsEnabled()
    {
        $api = \Mockery::mock(UnisenderApi::class);
        $api->shouldReceive('sendSms')->andThrow(
            new RequestException('404', new Request('GET', '/example'))
        );

        $notifiable = new FakeNotifiable();

        $message = \Mockery::mock(UnisenderMessage::class.'[to]');
        $message->shouldReceive('to')->andReturn(null);
        $message->silent(true);

        $notification = \Mockery::mock(FakeNotification::class);
        $notification->shouldReceive('toUnisender')->andReturn($message);

        $result = (new UnisenderChannel($api))->send($notifiable, $notification);

        $this->assertNull($result);
    }
}
