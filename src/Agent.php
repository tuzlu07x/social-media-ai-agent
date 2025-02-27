<?php

namespace Tuzlu07x\SocialMediaAIAgent;

use Tuzlu07x\SocialMediaAIAgent\AI\AIAdapterInterface;
use Tuzlu07x\SocialMediaAIAgent\SocialMedia\SocialMediaAdapterInterface;

class Agent
{
    /** @var SocialMediaAdapterInterface[] */
    private array $socialMediaAdapters = [];
    private AIAdapterInterface $aiAdapter;

    public function __construct(AIAdapterInterface $aiAdapter)
    {
        $this->aiAdapter = $aiAdapter;
    }

    public function addSocialMediaAdapter(string $platform, SocialMediaAdapterInterface $adapter): void
    {
        $this->socialMediaAdapters[$platform] = $adapter;
    }

    public function getSocialMediaAdapters(): array
    {
        return $this->socialMediaAdapters;
    }

    public function processInput(string $input): string
    {
        $aiOutput = $this->aiAdapter->process($input);
        return $this->executeAction($aiOutput);
    }

    private function executeAction(string $output): string
    {
        if (preg_match('/tweet at (.*): (.*)/i', $output, $matches)) {
            $time = $matches[1] ?? 'now';
            $message = $matches[2] ?? $output;
            return $this->socialMediaAdapters['twitter']->postText($message, $time);
        }

        if (preg_match('/instagram post: (.*)/i', $output, $matches)) {
            $imagePath = trim($matches[1]);
            return $this->socialMediaAdapters['instagram']->postImage($imagePath);
        }

        return "Unknown action: $output";
    }
}
