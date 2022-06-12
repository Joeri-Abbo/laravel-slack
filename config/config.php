<?php

/**
 * Example of Slack integration.
 * app('slack.log')->sendMessage('This is a test message')
 */

return [
    'bearer_token' => env('SLACK_BEARER_TOKEN'),
    'channels' => [
//        'log' => '#ID',
    ]
];
