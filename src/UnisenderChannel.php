<?php

namespace NotificationChannels\Unisender;

use Illuminate\Notifications\Notification;

class UnisenderChannel
{
    /**
     * @var UnisenderApi
     */
    protected $api;

    /**
     * UnisenderChannel constructor.
     *
     * @param UnisenderApi $api
     */
    public function __construct(UnisenderApi $api)
    {
        $this->api = $api;
    }

    /**
     * @param mixed        $notifiable
     * @param Notification $notification
     *
     * @return mixed
     */
    public function send($notifiable, Notification $notification)
    {
        $to = $notifiable->routeNotificationFor('unisender');

        if (!$to) {
            throw new \InvalidArgumentException('No receivers.');
        }

        if (!method_exists($notification, 'toUnisender')) {
            throw new \InvalidArgumentException('Method "toUnisender" does not exists on given notification instance.');
        }

        /** @var UnisenderMessage $message */
        $message = call_user_func_array([$notification, 'toUnisender'], [$notifiable]);

        if (!($message instanceof UnisenderMessage)) {
            throw new \InvalidArgumentException('Message is not an instance of UnisenderMessage.');
        }

        $message->to($to);

        return $this->sendMessage($message);
    }

    protected function sendMessage(UnisenderMessage $message)
    {
        if (!is_null($message->token)) {
            $this->api->setToken($message->token);
        }

        try {
            return $this->api->sendSms($message);
        } catch (\Exception $e) {
            if (!$message->silent) {
                throw $e;
            }
        }

        return null;
    }
}
