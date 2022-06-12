<?php

namespace  JoeriAbbo\Slack\Client;

class MockSlackClient extends Client
{
    /**
     * @return true
     */
    public function sendMessage(string $message): bool
    {
        return true;
    }
}
