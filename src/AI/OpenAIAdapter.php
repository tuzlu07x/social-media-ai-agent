<?php

namespace Tuzlu07x\SocialMediaAIAgent\AI;

use GuzzleHttp\Client;

class OpenAIAdapter implements AIAdapterInterface
{
    private Client $client;
    private string $apiKey;
    private string $modelName = 'gpt-3.5-turbo';

    public function __construct(string $apiKey, string $modelName = 'gpt-3.5-turbo')
    {
        $this->apiKey = $apiKey;
        $this->modelName = $modelName;
        $this->client = new Client();
    }

    public function process(string $input): string
    {
        try {
            $response = $this->client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => "Bearer $this->apiKey",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $this->modelName,
                    'messages' => [['role' => 'user', 'content' => $input]],
                ],
            ]);
            $data = json_decode($response->getBody(), true);
            return $data['choices'][0]['message']['content'] ?? 'Error: OpenAI did not respond.';
        } catch (\Exception $e) {
            return "OpenAI hatası: " . $e->getMessage();
        }
    }
}
