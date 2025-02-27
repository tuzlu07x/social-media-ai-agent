<?php

namespace Tuzlu07x\SocialMediaAIAgent\AI;

interface AIAdapterInterface
{
    public function process(string $input): string;
}
