<?php

namespace Viodev;

use Illuminate\Support\Facades\Http;

class LaravelSlack
{
    const INFO = 'info';
    const SUCCESS = 'success';
    const WARNING = 'warning';
    const ERROR = 'error';

    private string $channel = 'info', $level = self::INFO;
    private ?string $title = null;
    private array $blocks = [], $lines = [];

    private string $username, $webhook_url;

    public function __construct($username, $webhook_url)
    {
        $this->username = $username;
        $this->webhook_url = $webhook_url;
    }

    public function to($channel): self
    {
        $this->channel = $channel;
        return $this;
    }

    public function title($title) : self
    {
        $this->title = $title;
        return $this;
    }

    public function block($text, $sub_text = null) : self
    {
        $this->addToBlock($text, $sub_text);
        return $this;
    }

    public function addToBlock($text, $sub_text = null) : self
    {
        $this->lines[] = is_null($sub_text) ? [$text] : [$text, $sub_text];
        return $this;
    }

    public function newBlock($text, $sub_text = null) : self
    {
        $this->completeBlock()->addToBlock($text, $sub_text);
        return $this;
    }

    public function newLine() : self
    {
        return $this->addToBlock('');
    }

    public function success(): self
    {
        $this->level = self::SUCCESS;
        return $this;
    }

    public function warning(): self
    {
        $this->level = self::WARNING;
        return $this;
    }

    public function error(): self
    {
        $this->level = self::ERROR;
        return $this;
    }

    public function message($message) : bool
    {
        return $this->title($message)->send();
    }

    public function send() : bool
    {
        $this->completeBlock();

        $data['username'] = $this->username;
        $data['channel'] = $this->channel;

        if(!is_null($this->title)){
            $data['text'] = "*" . $this->title . "*";
        }

        foreach ($this->blocks as $block){
            $data['attachments'][] = $block;
        }

        try {
            $response = Http::asJson()->post($this->webhook_url, $data);
        } catch (\Exception $e){
            return false;
        }

        if($response->failed()) return false;

        return true;
    }

    private function completeBlock() : self
    {
        if(!empty($this->lines)){
            $body = "";

            foreach ($this->lines as $line) {
                if (count($line) == 1) {
                    $body .= "$line[0]\n";
                } else {
                    $body .= "*$line[0]:* $line[1]\n";
                }
            }

            $block['type'] = 'mrkdwn';
            $block['color'] = $this->getColor();
            $block['text'] = $body;

            $this->blocks[] = $block;

            $this->lines = [];
        }

        return $this;
    }

    private function getColor(): string
    {
        if($this->level == self::SUCCESS) return '#28a745';
        if($this->level == self::WARNING) return '#ffc107';
        if($this->level == self::ERROR) return '#dc3545';

        return '#a5a5a5';
    }
}