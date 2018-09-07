<?php

namespace NotificationChannels\Panacea;

use Illuminate\Notifications\Notification;

class PanaceaChannel
{
    /** @var \NotificationChannels\Panacea\PanaceaApi */
    protected $panacea;

    public function __construct(PanaceaApi $panacea)
    {
        $this->panacea = $panacea;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed    $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     *
     * @throws  \NotificationChannels\Panacea\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {

        if (! $to = $notifiable->routeNotificationFor('panacea')) {
            return;
        }

        $message = $notification->toPanacea($notifiable);

        if (is_string($message)) {
            $message = new PanaceaMessage($message);
        }

        $this->panacea->send($to, $message->toArray());
    }
}
