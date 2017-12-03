<?php

namespace NotificationChannels\Unisender\Tests\Fakes;

use Illuminate\Notifications\Notification;
use NotificationChannels\Unisender\UnisenderChannel;
use NotificationChannels\Unisender\UnisenderMessage;

class FakeNotification extends Notification
{
    public function via($notifiable)
    {
        return [UnisenderChannel::class];
    }

    public function toUnisender($notifiable)
    {
        return (new UnisenderMessage())->from('Fake')->content('Fake Content');
    }
}
