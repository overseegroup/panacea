# Panacea Mobile
This package makes it easy to send notifications using [PanaceaMobile](https://www.panaceamobile.com/) with Laravel 5.5

## Installation

You can install the package via composer:

```bash
composer require overseegroup/panacea
```

Be sure to include the Service Provider in your config.app file:
```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\Panacea\PanaceaServiceProvider::class,
];
```


## Configuration

This pacakge assumes you already have an active and working account with Panacea Mobile and have access to your API token.

Once you have these deetals on hand, add to your `config/services.php` file:

```php
// config/services.php

'panacea' => [
    'login'  => env('PANACEA_LOGIN'), // Your Username
    'api' => env('PANACEA_API'), // Your API Token
    'url' => env('PANACEA_URL'), // Your API URL - default: bli.panaceamobile.com/json
    'sender' => '0000000000' // Default Sending Number
]
```

## Implementation

You can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\Panacea\PanaceaMessage;
use NotificationChannels\Panacea\PanaceaChannel;

class SendSMS extends Notification
{
    public function via($notifiable)
    {
        return [PanaceaChannel::class];
    }

    public function toPanacea($notifiable)
    {
        return (new PanaceaMessage())
            ->content("A test message from Laravel.");
    }
}
```


## Notifiable Configuration

In order for this package to know which number to send the message to, it will look for the `mobile_number` attribute of the Notifiable model. Add the following code to your Notifiable model:

```php

 use Notifiable;

    /**
     * Route notifications for the Nexmo channel.
     *
     * @return string
     */
    public function routeNotificationForPanaceaMobile()
    {
        return $this->mobile_number; // or whatever database field you are storing the number field under
        
        // or you can hard code the number:
        //   return '27000000000';
    }
    
    ```
