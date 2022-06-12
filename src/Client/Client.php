<?php

namespace JoeriAbbo\Slack\Client;

/**
 * abstract client for slack channel
 */
class Client
{
    /**
     * @var Slack
     */
    private Slack $client;

    /**
     * Just a construct
     */
    public function __construct(string $bearerToken, string $channel)
    {
        $client = new \JoeriAbbo\Slack\Client\Slack();

        $this->client = $client
            ->setChannel($channel)
            ->setBearerToken($bearerToken);
    }

    /**
     * Send message
     * @param string $message
     * @return void
     * @throws \Exception
     */
    public function sendMessage(string $message)
    {
        $this->client->sendMessage($message);
    }
}
