<?php

namespace Tuzlu07x\SocialMediaAIAgent\AI;

use GuzzleHttp\Client;

class GrokAdapter implements AIAdapterInterface
{
    private Client $client;
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client();
    }

    public function process(string $input): string
    {
        try {
            $response = $this->client->post('https://api.x.ai/v1/grok', [
                'headers' => [
                    'Authorization' => "Bearer $this->apiKey",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'input' => $input,
                ],
            ]);
            $data = json_decode($response->getBody(), true);
            return $data['response'] ?? 'Error: Grok did not respond.';
        } catch (\Exception $e) {
            return "Grok error: " . $e->getMessage();
        }
    }
}
