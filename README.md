# Laravel slack

Send messages to slack from Laravel application.

### Installation

```
composer require viodev/laravel-slack
```

### Publish config

```
php artisan vendor:publish --tag="slack-config"
```

## Usage

### Basic message

Send simple message

```
use Viodev\Slack;

Slack::to('info')->message('My simple message');
```

#### Send blocks

Send complex message with contextual blocks

```
use Viodev\Slack;

// Create slack instance with title going to info channel

$slack = Slack::to('info')->title('My message title');

// Start first block with success trim

$slack->block('Key 1', 'Value 2')->success(); // Set success trim for current block
$slack->addToBlock('Key 2', 'Value 2');
$slack->addToBlock('Key 3', 'Value 3');

// Start 2nd block with error trim

$slack->newBlock('Errors', '1 error occurred');
$slack->addToBlock('Error 1', 'Exception 1')->error(); // Set error trim for current block

// Complete block and send

$slack->send();

```



