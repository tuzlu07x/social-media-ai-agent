# Social Media AI Agent

**Social Media AI Agent** is an open-source PHP library that empowers developers to automate social media interactions using artificial intelligence. This package integrates with popular platforms like Twitter, Instagram, TikTok, and LinkedIn, and supports multiple AI models such as OpenAI, DeepSeek, HuggingFace, Gemini, and Grok. Whether you want to schedule posts, reply to messages, analyze trends, or process custom inputs, this agent provides a flexible and extensible framework to streamline your social media workflows.

## Features

- **Multi-Platform Support**: Connect to Twitter and Instagram.
- **AI Integration**: Leverage OpenAI, DeepSeek, HuggingFace, Grok, or add your custom AI models.
- **Dynamic Actions**: Post text, share images, reply to messages, fetch trending topics, and more.
- **Scheduling**: Schedule posts for future execution (real scheduler implementation pending).
- **Modular Design**: Built with interfaces for easy extension and maintenance.
- **Open Source**: Released under the MIT License, free to use and contribute.

## Requirements

- PHP 8.0 or higher
- Composer
- API keys for social media platforms and AI services you plan to use

## Installation

Install the package via Composer:

```bash
composer require tuzlu07x/social-media-ai-agent
```

Alternatively, clone the repository and install dependencies manually:

```bash
git clone https://github.com/tuzlu07x/social-media-ai-agent.git
cd social-media-ai-agent
composer install
```

## Configuration

To use the agent, you need to configure API keys for the social media platforms and AI models. It’s recommended to store these in a .env file and load them using a library like vlucas/phpdotenv.

### Example .env File

```env
OPENAI_API_KEY=your_openai_key
DEEPSEEK_API_KEY=your_deepseek_key
HF_API_KEY=your_huggingface_key
GEMINI_API_KEY=your_gemini_key
GROK_API_KEY=your_grok_key
TWITTER_API_KEY=your_twitter_api_key
TWITTER_API_SECRET=your_twitter_api_secret
TWITTER_ACCESS_TOKEN=your_twitter_access_token
TWITTER_ACCESS_TOKEN_SECRET=your_twitter_access_token_secret
INSTAGRAM_ACCESS_TOKEN=your_instagram_access_token
```

### Loading Environment Variables

If you’re using `phpdotenv`, add this to your script:

```bash
composer require vlucas/phpdotenv
```

Then load the `.env` file:

```php
require __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
```

## Usage

Command Line Interface (CLI)
The package provides a CLI tool (`bin/agent`) to interact with the agent.

### Basic Syntax

```bash
php bin/agent agent:run "<input>" --ai=<ai_model> --platform=<platform>
```

`<input>`: The input string to process (e.g., "Tweet at 8 PM: Hello World").
`--ai`: The AI model to use (e.g., `openai`, `deepseek`, `huggingface`, `gemini`, `grok`). Default: openai.
`--platform`: The social media platform (e.g., twitter, instagram). Default: twitter.

1. Schedule a Tweet with OpenAI

```bash
php bin/agent agent:run "Tweet at 8 PM: Hello Twitter" --ai=openai --platform=twitter
```

Output: Scheduled action: postText at 8 PM 2. Post an Image to Instagram with DeepSeek

2. Post an Image to Instagram with DeepSeek

```bash
php bin/agent agent:run "Instagram post: /path/to/image.jpg" --ai=deepseek --platform=instagram
```

Output: Image shared to Instagram:

3. Invalid Input

```bash
php bin/agent agent:run "Hello" --ai=unknown --platform=twitter
```

Output: Error: AI model: unknown

## Programmatic Usage

You can also use the agent directly in your PHP code:

```php
require 'vendor/autoload.php';

use Tuzlu07x\SocialMediaAIAgent\Agent;
use Tuzlu07x\SocialMediaAIAgent\AI\OpenAIAdapter;
use Tuzlu07x\SocialMediaAIAgent\SocialMedia\TwitterAdapter;

$agent = new Agent(new OpenAIAdapter(getenv('OPENAI_API_KEY')));
$agent->addSocialMediaAdapter('twitter', new TwitterAdapter(
    getenv('TWITTER_API_KEY'),
    getenv('TWITTER_API_SECRET'),
    getenv('TWITTER_ACCESS_TOKEN'),
    getenv('TWITTER_ACCESS_TOKEN_SECRET')
));

$result = $agent->processInput("Tweet at 8 PM: Hello Twitter");
echo $result; // "scheduled time: postText at 8 PM"
```

### Supported Platforms

`Twitter`: Post tweets, reply to messages (more features in progress).
`Instagram`: Share images (text posts and replies in progress).
`TikTok`: Skeleton implemented, full support coming soon.
`LinkedIn`: Skeleton implemented, full support coming soon.

### Supported AI Models

`OpenAI`: Fully supported with the GPT-3.5-turbo model.
`DeepSeek`: Experimental support (API endpoint assumed).
`HuggingFace`: Supports inference API with customizable models.
`Grok`: Experimental support (xAI API assumed).

### Extending the Agent

1. Adding a New Social Media Platform
2. Create a new adapter in `src/SocialMedia/` implementing SocialMediaAdapterInterface.
3. Update `ServerCommand::addSocialMediaAdapter` to include your platform.
4. Adding a New AI Model
5. Create a new adapter in `src/AI/` implementing `AIAdapterInterface`.
6. Update `ServerCommand::createAIAdapter` to include your model.

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository.
2. Create a new branch (git checkout -b feature/your-feature).
3. Commit your changes (git commit -m "Add your feature").
4. Push to your branch (git push origin feature/your-feature).
5. Open a Pull Request.

## Roadmap

- Real scheduler for timed actions (e.g., using cron or a queue system).
- Full implementations for TikTok and LinkedIn adapters.
- Message retrieval and trending topic analysis.
- Improved error handling and logging.

# License

This project is licensed under the MIT License

# Credits

Developed by Fatih Tuzlu (tuzlu07x). Special thanks to the open-source community!
