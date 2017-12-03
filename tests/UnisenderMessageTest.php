<?php

namespace NotificationChannels\Unisender\Tests;

use NotificationChannels\Unisender\UnisenderMessage;

class UnisenderMessageTest extends TestCase
{
    public function testItShouldSetAccessToken()
    {
        $message = new UnisenderMessage();
        $this->assertNull($message->token);

        $message->usingApiToken('example');
        $this->assertSame('example', $message->token);
    }

    public function testItShouldSetSilentMode()
    {
        $message = new UnisenderMessage();
        $this->assertFalse($message->silent);

        $message->silent(true);
        $this->assertTrue($message->silent);
    }

    public function testItShouldSetReceivers()
    {
        $message = new UnisenderMessage();
        $this->assertNull($message->to);

        $message->to('example');
        $this->assertSame('example', $message->to);
    }

    public function testItShouldSetReceiversFromArray()
    {
        $message = new UnisenderMessage();
        $this->assertNull($message->to);

        $message->to(['first', 'second', 'third']);
        $this->assertSame(implode(',', ['first', 'second', 'third']), $message->to);
    }

    public function testItShouldSetSender()
    {
        $message = new UnisenderMessage();
        $this->assertNull($message->from);

        $message->from('example');
        $this->assertSame('example', $message->from);
    }

    public function testItShouldSetContent()
    {
        $message = new UnisenderMessage();
        $this->assertNull($message->content);

        $message->content('example');
        $this->assertSame('example', $message->content);
    }
}
