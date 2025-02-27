<?php

namespace Tuzlu07x\SocialMediaAIAgent\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tuzlu07x\SocialMediaAIAgent\Agent;
use Tuzlu07x\SocialMediaAIAgent\AI\OpenAIAdapter;
use Tuzlu07x\SocialMediaAIAgent\AI\DeepSeekAdapter;
use Tuzlu07x\SocialMediaAIAgent\AI\HuggingFaceAdapter;
use Tuzlu07x\SocialMediaAIAgent\AI\GrokAdapter;
use Tuzlu07x\SocialMediaAIAgent\SocialMedia\TwitterAdapter;
use Tuzlu07x\SocialMediaAIAgent\SocialMedia\InstagramAdapter;

class ServerCommand extends Command
{
    protected static $defaultName = 'agent:run';

    protected function configure(): void
    {
        $this->setDescription('Run the Social Media AI Agent')
            ->addArgument('input', InputArgument::REQUIRED, 'The input for the AI agent')
            ->addOption('ai', null, InputOption::VALUE_REQUIRED, 'AI model to use (openai, deepseek, huggingface, gemini, grok)', 'openai')
            ->addOption('platform', null, InputOption::VALUE_REQUIRED, 'Social media platform to use (twitter, instagram)', 'twitter');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // AI modelini seç
        $aiModel = $input->getOption('ai');
        $aiAdapter = $this->createAIAdapter($aiModel);

        if (!$aiAdapter) {
            $output->writeln("Hata: Geçersiz AI modeli: $aiModel");
            return Command::FAILURE;
        }
        $agent = new Agent($aiAdapter);

        $platform = $input->getOption('platform');
        $this->addSocialMediaAdapter($agent, $platform);

        if (empty($agent->getSocialMediaAdapters())) {
            $output->writeln("Hata: Geçersiz platform: $platform");
            return Command::FAILURE;
        }

        // Girdiyi işle
        $result = $agent->processInput($input->getArgument('input'));
        $output->writeln($result);

        return Command::SUCCESS;
    }

    private function createAIAdapter(string $model): ?object
    {
        return match (strtolower($model)) {
            'openai' => new OpenAIAdapter(getenv('OPENAI_API_KEY') ?: 'YOUR_OPENAI_KEY'),
            'deepseek' => new DeepSeekAdapter(getenv('DEEPSEEK_API_KEY') ?: 'YOUR_DEEPSEEK_KEY'),
            'huggingface' => new HuggingFaceAdapter(getenv('HF_API_KEY') ?: 'YOUR_HF_KEY'),
            'grok' => new GrokAdapter(getenv('GROK_API_KEY') ?: 'YOUR_GROK_KEY'),
            default => null,
        };
    }

    private function addSocialMediaAdapter(Agent $agent, string $platform): void
    {
        switch (strtolower($platform)) {
            case 'twitter':
                $agent->addSocialMediaAdapter('twitter', new TwitterAdapter(
                    getenv('TWITTER_API_KEY') ?: 'TWITTER_API_KEY',
                    getenv('TWITTER_API_SECRET') ?: 'TWITTER_API_SECRET',
                    getenv('TWITTER_ACCESS_TOKEN') ?: 'ACCESS_TOKEN',
                    getenv('TWITTER_ACCESS_TOKEN_SECRET') ?: 'ACCESS_TOKEN_SECRET'
                ));
                break;
            case 'instagram':
                $agent->addSocialMediaAdapter('instagram', new InstagramAdapter(
                    getenv('INSTAGRAM_ACCESS_TOKEN') ?: 'INSTAGRAM_ACCESS_TOKEN'
                ));
                break;
        }
    }
}
