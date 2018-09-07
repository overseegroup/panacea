<?php

namespace NotificationChannels\Panacea;

use DomainException;
use GuzzleHttp\Client as HttpClient;
use NotificationChannels\Panacea\Exceptions\CouldNotSendNotification;

class PanaceaApi
{
    const FORMAT_JSON = 3;

    /** @var string */
    protected $apiUrl = 'bli.panaceamobile.com/json';

    /** @var HttpClient */
    protected $httpClient;

    /** @var string */
    protected $login;

    /** @var string */
    protected $api;

    /** @var string */
    protected $sender;

    public function __construct($login, $api, $sender)
    {
        $this->login = $login;
        $this->api = $api;
        $this->sender = $sender;

        $this->httpClient = new HttpClient([
            'timeout' => 5,
            'connect_timeout' => 5,
        ]);
    }

    /**
     * @param  string  $recipient
     * @param  array   $params
     *
     * @return array
     *
     * @throws CouldNotSendNotification
     */
    public function send($recipient, $params)
    {
        $params = array_merge([
			'action' 	=> 'message_send',
            'username'  => $this->login,
            'password'  => $this->api,
            'from' 		=> $this->sender,
            'to' 		=> $recipient,
        ], $params);


        try {
            $request = $this->httpClient->get(
			$this->apiUrl,
				[
					'query' => $params
				]
			);

            $response = json_decode($request->getBody(), true);

            if ( ! isset($response['status']) || $response['status'] != 1) {
                throw new DomainException($response['message'], $response['status']);
            }

            return $response;
        } catch (DomainException $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        }
    }
}
