<?php

namespace Tuzlu07x\SocialMediaAIAgent\SocialMedia;

use GuzzleHttp\Client;

class InstagramAdapter implements SocialMediaAdapterInterface
{
    private Client $client;
    private string $accessToken;

    public function __construct(string $accessToken)
    {
        $this->accessToken = $accessToken;
        $this->client = new Client();
    }

    public function postText(string $message, string $scheduleTime = 'now'): string
    {
        return "Now you cannot post text on Instagram.";
    }

    public function postImage(string $imagePath, string $caption = '', string $scheduleTime = 'now'): string
    {
        if ($scheduleTime !== 'now') {
            return $this->scheduleAction('postImage', [$imagePath, $caption], $scheduleTime);
        }
        try {
            $mediaResponse = $this->client->post("https://graph.instagram.com/v20.0/me/media", [
                'form_params' => [
                    'image_url' => $imagePath,
                    'caption' => $caption,
                    'access_token' => $this->accessToken
                ]
            ]);
            $mediaData = json_decode($mediaResponse->getBody(), true);
            $mediaId = $mediaData['id'];

            $publishResponse = $this->client->post("https://graph.instagram.com/v20.0/me/media_publish", [
                'form_params' => [
                    'creation_id' => $mediaId,
                    'access_token' => $this->accessToken
                ]
            ]);
            if ($publishResponse->getStatusCode() !== 200) return "Image did not share: $imagePath with caption: $caption";
            return "Shared image: $imagePath with caption: $caption";
        } catch (\Exception $e) {
            return "Instagram hatası: " . $e->getMessage();
        }
    }

    public function replyToMessage(string $messageId, string $reply): string
    {
        return "Now you cannot reply on Instagram.";
    }

    public function getMessages(): array
    {
        return []; // DM’ler için API çağrısı eklenecek
    }

    public function getTrendingTopics(): array
    {
        return []; // Instagram’da trend analizi için ek API gerek
    }

    private function scheduleAction(string $method, array $args, string $time): string
    {
        return "Zamanlanmış işlem: $method at $time";
    }
}
