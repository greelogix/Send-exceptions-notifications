<?php

namespace Greelogix\ExceptionNotifications\Exceptions;

use Throwable;
use Mail;
use Exception;
use Config;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;

class ExceptionNotificationsHandler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];
    
    public function report(Throwable $exception)
    {
        if ($this->shouldReport($exception)) {
            $this->sendExceptionEmail($exception);
        }

        parent::report($exception);
    }

    public function sendExceptionEmail(Throwable $exception)
    {

        try {
            $html = ExceptionHandler::convertExceptionToResponse($exception);
             
            if ((!empty(config('exceptionNotifications.to_email_address')) && is_array(config('exceptionNotifications.to_email_address'))) || !empty(config('exceptionNotifications.from_email_address'))) {
                foreach (config('exceptionNotifications.to_email_address') as $key => $rEmail) {
                    Mail::send([], [], function ($message) use ($rEmail, $html) {
                        $message->from(config('exceptionNotifications.from_email_address'), "admin_mail")->to($rEmail)
                        ->subject(config('exceptionNotifications.email_subject'))
                        ->setBody($html, 'text/html'); // for HTML rich messages
                    });
                }
            }
        } catch (Exception $e) {
        }
    }

    
    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }
}
