# Unisender Notifications Channel For Laravel 5.5+

[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]
[![ScrutinizerCI][ico-scrutinizer]][link-scrutinizer]
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)

## Requirements
This package requires PHP 7.1 or higher.

## Installation

You can install the package via composer:

``` bash
$ composer require tzurbaev/unisender-notifications-channel
```

### Setting up the Unisender Service

Add Unisender API key to the `config/services.php` file:

```php
'unisender' => [
    'api-key' => 'KEY_HERE',
],
```

This key will be used as default key for all notifications. You can always override it for concrete notifications.

## Documentation

Add `UnisenderChannell::class` to notification's `via` method and implement `toUnisender($notifiable)` method:

```php
<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Unisender\UnisenderChannel;
use NotificationChannels\Unisender\UnisenderMessage;

class SubscriptionActivatedNotification extends Notification
{
    public function via($notifiable)
    {
        return [UnisenderChannel::class];
    }
    
    public function toUnisender($notifiable)
    {
        return (new UnisenderMessage)
            ->from('Laravel')
            ->content('Your subscription is active!');
    }
}
```

Also, your `Notifiable` classes must have the `routeNotificationForUnisender` method, which should return single phone number (E.164 format) or list of phone numbers (comma-separated or in array).

```php
<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    
    public function routeNotificationForUnisender()
    {
        return '+79641234567'; // or ['+79641234567', '+79831234567'];
    }
}
```

### Available Methods

- `UnisenderMessage::silent(bool $flag = true)` - sets the 'silent mode' - Channel won't throw any exception while sending SMS;
- `UnisenderMessage::usingAccessToken($token)` - reset token only for the current message;
- `UnisenderMessage::from(string $from)` - set the sender name;
- `UnisenderMessage::content(string $content)` - set the message content.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email zurbaev@gmail.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://poser.pugx.org/tzurbaev/unisender-notifications-channel/version?format=flat
[ico-license]: https://poser.pugx.org/tzurbaev/unisender-notifications-channel/license?format=flat
[ico-travis]: https://api.travis-ci.org/tzurbaev/unisender-notifications-channel.svg?branch=master
[ico-styleci]: https://styleci.io/repos/112904100/shield?branch=master&style=flat
[ico-scrutinizer]: https://scrutinizer-ci.com/g/tzurbaev/unisender-notifications-channel/badges/quality-score.png?b=master

[link-packagist]: https://packagist.org/packages/tzurbaev/unisender-notifications-channel
[link-travis]: https://travis-ci.org/tzurbaev/unisender-notifications-channel
[link-styleci]: https://styleci.io/repos/112904100
[link-scrutinizer]: https://scrutinizer-ci.com/g/tzurbaev/unisender-notifications-channel/
[link-author]: https://github.com/tzurbaev
