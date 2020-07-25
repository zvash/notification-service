<?php

namespace App\Services;


use Illuminate\Support\Facades\Log;

class EmailSender implements Sendable
{

    /**
     * @var string $address
     */
    protected $address;

    /**
     * @var string $template
     */
    protected $template;

    /**
     * @var array $params
     */
    protected $params;

    /**
     * EmailSender constructor.
     * @param string $address
     * @param string $template
     * @param array $params
     */
    public function __construct(string $address, string $template, array $params)
    {
        $this->address = $address;
        $this->template = $template;
        $this->params = $params;
    }
    
    public function send() {
        $subject = trans('messages.' . $this->template . '_email_subject', $this->params);
        $body = "";
        try {
            $body = view(config('app.locale') . '.emails.' . $this->template, $this->params)->render();
        } catch (\Throwable $e) {
        }
        Log::debug($subject);
        Log::debug($body);
    }


}