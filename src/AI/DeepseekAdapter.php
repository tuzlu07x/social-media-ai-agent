<?php

namespace Tuzlu07x\SocialMediaAIAgent\AI;

use GuzzleHttp\Client;

class DeepSeekAdapter implements AIAdapterInterface
{
    private Client $client;
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client([
            'base_uri' => 'https://api.deepseek.com/v1/',
        ]);
    }

    public function process(string $input): string
    {
        try {
            $response = $this->client->post('chat/completions', [
                'headers' => [
                    'Authorization' => "Bearer $this->apiKey",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'deepseek-chat',
                    'messages' => [['role' => 'user', 'content' => $input]],
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['choices'][0]['message']['content'] ?? 'Error: DeepSeek did not respond.';
        } catch (\Exception $e) {
            return "DeepSeek hatası: " . $e->getMessage();
        }
    }
}
