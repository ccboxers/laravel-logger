<?php

namespace Layman\LaravelLogger\Servers;

use Layman\LaravelLogger\Models\Logger;
use Layman\LaravelLogger\Structs\LoggerStruct;

class LoggerServer extends LoggerStruct
{
    public function create(): Logger
    {
        return Logger::query()->create([
            'userid' => $this->getUserid(),
            'type' => $this->getType(),
            'model' => $this->getModel(),
            'old' => $this->getOld(),
            'new' => $this->getNew(),
        ]);
    }

    public function delete(int $id): int
    {
        $method = config('logger.delete') ? 'forceDelete' : 'delete';
        return Logger::query()
            ->where('id', $id)
            ->$method();
    }
}
