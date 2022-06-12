<?php

namespace JoeriAbbo\Slack\Client;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class Slack
{
    /**
     * @var Client|null
     */
    private ?Client $client = null;

    /**
     * @var string|null
     */
    private ?string $channel = null;

    /**
     * @var string|null
     */
    private ?string $bearer_token = null;
    /**
     * @var string|null
     */
    private ?string $message = null;

    /**
     * Get guzzle client
     */
    public function getClient(): Client
    {
        if (is_null($this->client)) {
            $this->client = new Client(['headers' =>
                [
                    'Authorization' => "Bearer {$this->getBearerToken()}",
                ]
            ]);
        }

        return $this->client;
    }

    /**
     * Set channel to post message in
     * @param string $channel
     * @return $this
     */
    public function setChannel(string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get the channel
     * @return string
     * @throws Exception
     */
    public function getChannel(): string
    {
        if (is_null($this->channel)) {
            throw new Exception('No channel set for slack.');
        }

        return $this->channel;
    }

    /**
     * Set bearer token
     * @param string $bearer_token
     * @return $this
     */
    public function setBearerToken(string $bearer_token): self
    {
        $this->bearer_token = $bearer_token;

        return $this;
    }

    /**
     * Get the bearer token
     * @return string
     * @throws Exception
     */
    public function getBearerToken(): string
    {
        if (is_null($this->bearer_token)) {
            throw new Exception('No bearer token set for slack.');
        }

        return $this->bearer_token;
    }

    /**
     * Set bearer token
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the bearer token
     * @return string
     * @throws Exception
     */
    public function getMessage(): string
    {
        if (is_null($this->message)) {
            throw new Exception('No message set.');
        }

        return $this->message;
    }

    /**
     * Url to post to slack
     * @return string
     */
    private function postUrl(): string
    {
        return 'https://slack.com/api/chat.postMessage';
    }

    /**
     * Send a message
     * @param string $message
     * @return void
     * @throws Exception
     */
    public function sendMessage(string $message)
    {
        $this->setMessage($message);
        $this->send();
    }

    /**
     * The real send logica
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function send(): bool
    {
        if (is_null($this->getMessage())) {
            throw new Exception('No message');
        }

        $request = $this->getClient()->post(
            $this->postUrl(),
            [
                RequestOptions::JSON => [
                    'channel' => $this->channel,
                    'blocks' =>
                        [
                            [
                                'type' => 'section',
                                'text' =>
                                    [
                                        'type' => 'mrkdwn',
                                        'text' => $this->getMessage()
                                    ]
                            ]
                        ]
                ]
            ]
        );

        if ($request->getStatusCode() !== 200) {
            throw new Exception(sprintf('Slack returned an %s status code something went wrong', $request->getStatusCode()));
        }

        $data = $request->getBody()->getContents();
        if (empty($data)) {
            throw new Exception('Got no response data form slack');
        }

        $data = json_decode($data);
        if ($data->ok == false || empty($data->ok)) {
            if (!empty($data->error)) {
                throw new Exception($data->error);
            } else {
                throw new Exception($data->response_metadata->messages[0]);
            }
        }

        return true;
    }
}
