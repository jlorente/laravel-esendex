<?php

/**
 * Part of the Esendex Laravel package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Esendex Laravel
 * @version    1.0.0
 * @author     Jose Lorente
 * @license    BSD License (3-clause)
 * @copyright  (c) 2018, Jose Lorente
 */

namespace Jlorente\Laravel\Esendex\Notifications\Channel;

use Esendex\Model\DispatchMessage;
use Esendex\Model\Message;
use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Jlorente\Laravel\Esendex\Esendex;
use Jlorente\Laravel\Esendex\Notifications\Messages\EsendexMessage;

/**
 * Class EsendexSmsChannel.
 * 
 * A notification channel to send SMS notifications via Esendex.
 *
 * @author Jose Lorente <jose.lorente.martin@gmail.com>
 */
class EsendexSmsChannel
{

    /**
     * The Esendex client instance.
     *
     * @var Esendex
     */
    protected $client;

    /**
     * Create a new Esendex channel instance.
     *
     * @param Esendex $client
     * @return void
     */
    public function __construct(Esendex $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param mixed  $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return \Esendex\Model\ResultItem
     */
    public function send($notifiable, Notification $notification)
    {
        if (!$to = $notifiable->routeNotificationFor('esendex', $notification)) {
            $to = $notifiable->phone_number;
            if (!$to) {
                return;
            }
        }

        $message = $notification->toEsendex($notifiable);
        if (is_string($message)) {
            $message = new EsendexMessage($message);
        }

        if (config('services.esendex.dry_run', false) === true) {
            return true;
        }

        try {
            return $this->client->dispatchService()->send(new DispatchMessage(
                                    $message->from ?? config('services.esendex.default_from', 'Laravel')
                                    , $to
                                    , trim($message->content)
                                    , Message::SmsType
            ));
        } catch (Exception $ex) {
            Log::critical('Esendex API Exception ', ['message' => $ex->getMessage()]);

            if (config('services.esendex.throw_exception_on_error', true)) {
                throw $ex;
            }

            return false;
        }
    }

}
