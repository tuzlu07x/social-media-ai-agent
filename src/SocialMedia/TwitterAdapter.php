<?php

namespace Tuzlu07x\SocialMediaAIAgent\SocialMedia;

use GuzzleHttp\Client;

class TwitterAdapter implements SocialMediaAdapterInterface
{
    private Client $client;
    private string $apiKey;
    private string $apiSecret;
    private string $accessToken;
    private string $accessTokenSecret;

    public function __construct(string $apiKey, string $apiSecret, string $accessToken, string $accessTokenSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->accessToken = $accessToken;
        $this->accessTokenSecret = $accessTokenSecret;
        $this->client = new Client();
    }

    public function postText(string $message, string $scheduleTime = 'now'): string
    {
        if ($scheduleTime !== 'now') {
            return $this->scheduleAction('postText', [$message], $scheduleTime);
        }
        try {
            $response = $this->client->post('https://api.twitter.com/2/tweets', [
                'oauth' => [
                    'consumer_key' => $this->apiKey,
                    'consumer_secret' => $this->apiSecret,
                    'token' => $this->accessToken,
                    'token_secret' => $this->accessTokenSecret,
                ],
                'json' => ['text' => $message]
            ]);
            if ($response->getStatusCode() !== 200) return "Tweet did not send: $message";
            return "tweet sent: $message";
        } catch (\Exception $e) {
            return "Twitter error: " . $e->getMessage();
        }
    }

    public function postImage(string $imagePath, string $caption = '', string $scheduleTime = 'now'): string
    {
        if ($scheduleTime !== 'now') {
            return $this->scheduleAction('postImage', [$imagePath, $caption], $scheduleTime);
        }
        return "Share image: $imagePath with caption: $caption";
    }

    public function replyToMessage(string $messageId, string $reply): string
    {
        try {
            $response = $this->client->post('https://api.twitter.com/2/tweets', [
                'oauth' => [
                    'consumer_key' => $this->apiKey,
                    'consumer_secret' => $this->apiSecret,
                    'token' => $this->accessToken,
                    'token_secret' => $this->accessTokenSecret,
                ],
                'json' => [
                    'text' => $reply,
                    'reply' => ['in_reply_to_tweet_id' => $messageId]
                ]
            ]);
            if ($response->getStatusCode() !== 200) return "The reply was not sent: $reply";
            return "The reply was sent: $reply";
        } catch (\Exception $e) {
            return "Twitter mistakes: " . $e->getMessage();
        }
    }

    public function getMessages(): array
    {
        return []; // DM’ler veya mention’lar için API çağrısı eklenecek
    }

    public function getTrendingTopics(): array
    {
        return []; // Trends API’si eklenecek
    }

    private function scheduleAction(string $method, array $args, string $time): string
    {
        return "Scheduled action: $method at $time";
    }
}
