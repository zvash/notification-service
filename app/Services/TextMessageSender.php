<?php

namespace App\Services;


use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Kavenegar\KavenegarApi;
use SMSGlobal\Exceptions\AuthenticationException;
use SMSGlobal\Exceptions\CredentialsException;
use SMSGlobal\Exceptions\InvalidPayloadException;
use SMSGlobal\Exceptions\InvalidResponseException;
use SMSGlobal\Exceptions\ResourceNotFoundException;

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
//        $api = new KavenegarApi( env('SMS_PROVIDER_API_KEY', ''));
//        $sender = env('DEFAULT_NUMBER', '');
//        $receptor = [$this->phone];
//        $api->Send($sender, $receptor, $body);
        \SMSGlobal\Credentials::set(env('SMS_GLOBAL_KEY'), env('SMS_GLOBAL_SECRET'));
        try {
            $sms = new \SMSGlobal\Resource\Sms();
            $response = $sms->sendToOne($this->phone, $body);
            Log::debug($response);
        } catch (CredentialsException $e) {
            Log::debug($e->getMessage());
        } catch (GuzzleException $e) {
            Log::debug($e->getMessage());
        } catch (AuthenticationException $e) {
            Log::debug($e->getMessage());
        } catch (InvalidPayloadException $e) {
            Log::debug($e->getMessage());
        } catch (InvalidResponseException $e) {
            Log::debug($e->getMessage());
        } catch (ResourceNotFoundException $e) {
            Log::debug($e->getMessage());
        }


    }


}