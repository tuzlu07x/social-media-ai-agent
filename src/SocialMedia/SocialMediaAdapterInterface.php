<?php

namespace Tuzlu07x\SocialMediaAIAgent\SocialMedia;

interface SocialMediaAdapterInterface
{
    public function postText(string $message, string $scheduleTime = 'now'): string;
    public function postImage(string $imagePath, string $caption = '', string $scheduleTime = 'now'): string;
    public function replyToMessage(string $messageId, string $reply): string;
    public function getMessages(): array;
    public function getTrendingTopics(): array;
}
