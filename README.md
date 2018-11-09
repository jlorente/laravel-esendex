Esendex SDK integration for Laravel
===================================
Laravel 5.6 integration for the Esendex SDK including a notification channel.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

With Composer installed, you can then install the extension using the following commands:

```bash
$ php composer.phar require jlorente/laravel-esendex
```

or add 

```json
...
    "require": {
        "jlorente/laravel-esendex": "*"
    }
```

to the ```require``` section of your `composer.json` file.

## Configuration

1. Register the ServiceProvider in your config/app.php service provider list.

config/app.php
```php
return [
    //other stuff
    'providers' => [
        //other stuff
        \Jlorente\Laravel\Esendex\EsendexServiceProvider::class,
    ];
];
```

2. If you want, you can add the following facade to the $aliases section.

config/app.php
```php
return [
    //other stuff
    'aliases' => [
        //other stuff
        'Esendex' => \Jlorente\Laravel\Esendex\Facades\Esendex::class,
    ];
];
```

3. Set the reference, username and password of your esendex account in the config/services.php 
file inside an array with 'esendex' as key.

config/services.php
```php
return [
    //other stuff
    'esendex' => [
        'reference' => 'YOUR ACCOUNT REFERENCE',
        'username' => 'YOUR ACCOUNT USERNAME',
        'password' => 'YOUR ACCOUNT PASSWORD',
    ];
];
```

## Usage

You can use the facade alias Esendex to execute services of the esendex sdk. The 
authentication params will be automaticaly injected.

```php
Esendex::dispatchService()->send(new DispatchMessage(
    $sender
    , $phone
    , $text
    , Message::SmsType
));
```

You can see a full list of the esendex sdk services in [this page](https://developers.esendex.com/SDKs/PHP-SDK).

## Notification Channel

A notification channel is included in this package and allows you to integrate 
the Esendex service with the Laravel notifications.

### Formatting Notifications

If a notification supports being sent as an SMS through Esendex, you should 
define a toEsendex method on the notification class. This method will receive a 
$notifiable entity and should return a Jlorente\Laravel\Esendex\Notifications\Messages\EsendexMessage 
instance or a string containing the message to send:

```php
/**
 * Get the Esendex / SMS representation of the notification.
 *
 * @param  mixed  $notifiable
 * @return \Jlorente\Laravel\Esendex\Notifications\Messages\EsendexMessage|string
 */
public function toEsendex($notifiable)
{
    return (new EsendexMessage)
                ->content('Your SMS message content');
}
```

Once done, you must add the notification channel in the array of the via() method 
of the notification:

```php
/**
 * Get the notification channels.
 *
 * @param  mixed  $notifiable
 * @return array|string
 */
public function via($notifiable)
{
    return [EsendexSmsChannel::class];
}
```

You can find more info about Laravel notification in [this page](https://laravel.com/docs/5.6/notifications).

## License 

Copyright &copy; 2018 José Lorente Martín <jose.lorente.martin@gmail.com>.

Licensed under the BSD 3-Clause License. See LICENSE.txt for details.
