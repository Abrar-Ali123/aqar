<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ErrorNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $exception;

    public function __construct(Throwable $exception)
    {
        $this->exception = $exception;
    }

    public function build()
    {
        return $this->markdown('emails.error-notification')
                    ->subject('خطأ حرج في التطبيق')
                    ->with([
                        'message' => $this->exception->getMessage(),
                        'file' => $this->exception->getFile(),
                        'line' => $this->exception->getLine(),
                        'trace' => $this->exception->getTraceAsString(),
                        'url' => request()->fullUrl(),
                        'user' => auth()->user() ? auth()->user()->name : 'زائر'
                    ]);
    }
}
