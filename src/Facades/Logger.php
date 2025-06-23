<?php

namespace Layman\LaravelLogger\Facades;

use Illuminate\Support\Facades\Facade;
use Layman\LaravelLogger\Servers\LoggerServer;

/**
 * @method static LoggerServer setUserid(int $userid)
 * @method static LoggerServer setType(string $type)
 * @method static LoggerServer setModel(?string $model)
 * @method static LoggerServer setOld(?array $old)
 * @method static LoggerServer setNew(?array $new)
 * @method static LoggerServer create()
 * @method static LoggerServer delete(int $id)
 *
 * @see \Layman\LaravelLogger\Servers\LoggerServer
 */
class Logger extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'logger';
    }
}
