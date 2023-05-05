<?php

namespace Viodev;

use Illuminate\Support\Facades\Facade;

/**
 * @method static LaravelSlack to($channel)
 * @method static LaravelSlack title($title)
 * @method static LaravelSlack block($text, $sub_text = null)
 * @method static LaravelSlack addToBlock($text, $sub_text = null)
 * @method static LaravelSlack newBlock($text, $sub_text = null)
 * @method static LaravelSlack newLine()
 * @method static LaravelSlack success()
 * @method static LaravelSlack warning()
 * @method static LaravelSlack error()
 * @method static bool message($message)
 * @method static bool send()
 * @see ReplyFactory
 */

class Slack extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return LaravelSlack::class;
    }
}