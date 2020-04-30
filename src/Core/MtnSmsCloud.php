<?php

namespace Mikofb\MtnSmsCloud\Core;

//require_once "./BaseApi.php";
use Mikofb\MtnSmsCloud\Core\BaseApi;

/**
 * This class is for for performing Api call on MTN SMS CLOUD server.
 * You can use it for: 
 *  - Create new sms campaign
 *  - Retrieves a campaign
 *  
 * @license MIT
 * @author Franck BROU <broukoffifranckmichael@gmail.com>
 * 
 */
class MtnSmsCloud extends BaseApi{

    /**
     * The base URL for the MTN SMS Cloud Api
     */
    private $base_api_url = "https://api.smscloud.ci/v1";

    /**
     * The SENDER ID of an existing sender
     *
     * @var string $sender_id
     */
    private $sender_id;

    /**
     * The Bearer Token of an existing MTN SMS Cloud account 
     *
     * @var string $auth_header
     */
    private $auth_header;

    /**
     * Init the Class
     * 
     * @param string $sender_id
     * @param string $auth_header
     */
    public function __construct($sender_id, $auth_header){
        parent::__construct($this->getBaseApiUrl());
        $this->setSenderID($sender_id);
        $this->setAuthHeader($auth_header);
    }

    /**
     * Return an array representation
     *
     * @return array
     */
    public function toArray()
    {
        return ['sender_id' => $this->getSenderID(), 'auth_header' => $this->getAuthHeader()];
    }

    /**
     * Send a new SMS Campaign
     *
     * @param array $recipients
     * @param string $content
     * @param array $body
     *
     * @return Request
     */
    public function newCampaign($recipients, $content)
    {
        // Recipients testing
        if (is_array($recipients) == false) {
            $this->sendError(400, false, "The parameter `recipients` must be an array");
        }
        if (count($recipients) == 0) {
            $this->sendError(400, false, "No phone number provided.");
        }
        // Scafolding the request's params
        $b = [
            "sender"=> $this->getSenderID(),
            "recipients"=> $recipients,
            "content"=> $content
        ];

        // Scafolding request's options
        $options = [
            'headers'=> [
                'Authorization: Bearer '.$this->getAuthHeader(),
                'Accept: application/json',
                'Content-Type: application/json',
                'Cache-Control: no-cache'
            ],
            'params' => $b
        ];
        // Sending POST Request
        return $this->post('campaigns', $options);
    }

    /**
     * Get a Campaign
     *
     * @param string $campaign_id
     *
     * @return Request
     */
    public function getCampaign($campaign_id)
    {
        if (is_null($campaign_id) || $campaign_id == "") {
            return $this->sendError(400, false, "No campaign ID provided.");
        }
        // Scafolding request's options
        $options = [
            'headers'=> [
                'Authorization: Bearer '.$this->getAuthHeader(),
                'Accept: application/json',
                'Content-Type: application/json',
                'Cache-Control: no-cache'
            ]
        ];
        // Sending Get Request
        return $this->get('campaigns/'.$campaign_id, $options);
    }

    /**
     * Retrieves all messages associated to the provided authentification Bearer token
     * 
     * @param string $status,
     * @param string $campaign_id,
     * @param string $dispatchedAt_before { (Datetime) 2020-02-14 14:14:00 => 20200214141400}
     * @param string $dispatchedAt_after { (Datetime) 2020-02-14 14:14:00 => 20200214141400}
     * @param string $updatedAt_before { (Datetime) 2020-02-14 14:14:00 => 20200214141400}
     * @param string $updatedAt_after { (Datetime) 2020-02-14 14:14:00 => 20200214141400}
     * @param int $page { Page number }
     * @param int $length { Nomber of messages per pages }
     * 
     * @return Request
     * 
     */
    public function getMessages($status = null, $campaign_id, $dispatchedAt_before, $dispatchedAt_after, $updatedAt_before = null, $updatedAt_after = null, $page = 1, $length = 2)
    {
        $options = [
            'headers'=> [
                'Authorization: Bearer '.$this->getAuthHeader(),
                'Accept: application/json',
                'Content-Type: application/json',
                'Cache-Control: no-cache'
            ],
            'params' => [
                'sender' => $this->getSenderID(),
                'status' => $status,
                'campaingId' => $campaign_id,
                'dispatchedAt_before' => $dispatchedAt_before,
                'dispatchedAt_after' => $dispatchedAt_after,
                'updatedAt_before' => $updatedAt_before,
                'updatedAt_after' => $updatedAt_after,
                'page' => $page,
                'length' => $length,
            ],
        ];

        // Sending Get Request
        $this->get('messages/outbox', $options);
    }

    /**
     * Set the Sender ID property
     * 
     * @param string $var
     * 
     * @return void
     */
    public function setSenderID($var)
    {
        $this->sender_id = $var;
    }

    /**
     * Set the Authorization Bearer token
     * 
     * @param string $var
     * 
     * @return void
     */
    public function setAuthHeader($var)
    {
        $this->auth_header = $var;
    }

    /**
     * Returns the base api url property
     *
     * @return void
     */
    public function getBaseApiUrl()
    {
        return $this->base_api_url;
    }

    /**
     * Returns the provided sender ID
     *
     * @return string
     * 
     * @return void
     */
    public function getSenderID()
    {
        return $this->sender_id;
    }

    /**
     * Returns the Athorization Bearer Token
     *
     * @return string
     * 
     * @return void
     */
    public function getAuthHeader()
    {
        return $this->auth_header;
    }
}