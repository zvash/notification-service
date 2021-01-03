<?php

namespace App\Services;


class NotificationService
{

    /**
     * @var array $acceptedGateWays
     */
    protected $acceptedGateWays = ['sms', 'email'];


    /**
     * @var array $payload
     */
    protected $payload;

    /**
     * @var array $recipients
     */
    private $recipients;

    /**
     * @var array $gateways
     */
    private $gateways;

    /**
     * @var string $template
     */
    private $template;

    /**
     * @var array $extraParams
     */
    private $extraParams;

    /**
     * @var array $senders
     */
    private $senders = [];

    /**
     * NotificationService constructor.
     * @param array $payload
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function notify()
    {
        $this->initializeSenders();
        foreach ($this->senders as $sender) {
            $sender->send();
        }
    }

    private function initializeSenders()
    {
        $this->recipients = $this->payload['recipients'];
        $this->extraParams = array_key_exists('extra_params', $this->payload) ?
            $this->payload['extra_params'] : [];
        $this->template = $this->payload['template'];
        $this->prepareEmails();
        $this->prepareTextMessages();
    }

    private function prepareEmails()
    {
        foreach ($this->recipients as $recipient) {
            if (array_key_exists('email', $recipient)) {
                $email = $recipient['email'];
                if ($email) {
                    $params = $this->extractParameters($recipient) + $this->extraParams;
                    $this->senders[] = new EmailSender($email, $this->template, $params);
                }
            }
        }
    }

    /**
     *
     */
    private function prepareTextMessages()
    {
        foreach ($this->recipients as $recipient) {
            $phone = $recipient['phone'];
            if ($phone) {
                $params = $this->extractParameters($recipient) + $this->extraParams;
                $this->senders[] = new TextMessageSender($phone, $this->template, $params);
            }

        }
    }

    /**
     * @param array $recipient
     * @return array
     */
    private function extractParameters(array $recipient)
    {
        $parameters = [];
        foreach ($recipient as $key => $value) {
            if (!is_array($value)) {
                $parameters[$key] = $value;
            }
        }
        return $parameters;
    }



}