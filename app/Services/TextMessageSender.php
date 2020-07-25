<?php

namespace App\Services;


use Illuminate\Support\Facades\Log;

class TextMessageSender implements Sendable
{
    /**
     * @var string $phone
     */
    protected $phone;

    /**
     * @var string $template
     */
    protected $template;

    /**
     * @var array $params
     */
    protected $params;

    /**
     * TextMessageSender constructor.
     * @param string $phone
     * @param string $template
     * @param array $params
     */
    public function __construct(string $phone, string $template, array $params)
    {
        $this->phone = $phone;
        $this->template = $template;
        $this->params = $params;
    }
    
    public function send()
    {
        $body = trans('messages.' . $this->template . '_sms', $this->params);
        Log::debug($body);
    }


}