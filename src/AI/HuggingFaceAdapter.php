<?php

namespace Tuzlu07x\SocialMediaAIAgent\AI;

use GuzzleHttp\Client;

class HuggingFaceAdapter implements AIAdapterInterface
{
    private Client $client;
    private string $apiKey;
    private string $model;

    public function __construct(string $apiKey, string $model = 'distilbert-base-uncased')
    {
        $this->apiKey = $apiKey;
        $this->model = $model;
        $this->client = new Client([
            'base_uri' => 'https://api-inference.huggingface.co/models/',
        ]);
    }

    public function process(string $input): string
    {
        try {
            $response = $this->client->post($this->model, [
                'headers' => [
                    'Authorization' => "Bearer $this->apiKey",
                    'Content-Type' => 'application/json',
                ],
                'json' => ['inputs' => $input],
            ]);

            $data = json_decode($response->getBody(), true);
            if (isset($data[0]['generated_text'])) {
                return $data[0]['generated_text'];
            } elseif (isset($data[0]['label'])) {
                return $data[0]['label'];
            }
            return "HuggingFace did not respond.";
        } catch (\Exception $e) {
            return "HuggingFace error: " . $e->getMessage();
        }
    }
}
