<?php

namespace NotificationChannels\Unisender\Tests\Fakes;

use Illuminate\Notifications\Notifiable;

class FakeNotifiable
{
    use Notifiable;

    public function routeNotificationForUnisender()
    {
        return '+79641234567';
    }
}
