<?php

namespace Layman\LaravelLogger\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Layman\LaravelLogger\Models\Logger;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('logger.auth');
    }

    public function index(Request $request): Factory|View|Application
    {
        $config = config('logger');
        $menus  = $this->menus($config, $request);

        $path   = $request->get('path');
        $page   = max((int)$request->get('page', 1), 1);
        $limit  = $config['limit'] ?? 25;
        $search = $request->get('search');

        $currentLogFile = null;
        $logLines       = [];
        $totalLines     = 0;
        $loggers       = [];

        if ($config['file']) {
            if ($path && file_exists(storage_path($path))) {
                $logPath        = storage_path($path);
                $currentLogFile = $path;
                $totalLines     = $this->countFileLines($logPath);
                $logLines       = $this->getLogPageLines($logPath, $page, $limit, $search);
            }
        }
        if ($config['database'] && $request->get('directory') === 'database' && $request->get('filename') === 'logger') {
            $loggers = $this->loggers($request, $limit);
        }
        return view('logger::home', compact('menus', 'page', 'limit', 'currentLogFile', 'logLines', 'totalLines', 'loggers'));
    }


    private function menus(array $config, Request $request): array
    {
        $logs      = [];
        $directory = $request->get('directory');
        $filename  = $request->get('filename');
        if ($config['file']) {
            foreach ($config['channels'] as $channel) {
                $channelConfig = config("logging.channels.{$channel}");
                if (!$channelConfig || !isset($channelConfig['path'])) {
                    continue;
                }
                $storageDir     = str_replace(storage_path() . DIRECTORY_SEPARATOR, '', $channelConfig['path']);
                $logDir         = dirname($storageDir);
                $files          = glob(storage_path($logDir) . DIRECTORY_SEPARATOR . '*.log');
                $logs[$channel] = array_map(function ($file) {
                    return [
                        'filename' => basename($file),
                        'path' => str_replace(storage_path() . DIRECTORY_SEPARATOR, '', $file),
                    ];
                }, $files);
            }
        }
        if ($config['database']) {
            $logs['database'] = [
                [
                    'filename' => 'logger',
                    'path' => null,
                ],
            ];
        }
        return compact('logs', 'directory', 'filename');
    }

    private function countFileLines(string $filePath): int
    {
        $lineCount = 0;
        $file      = new \SplFileObject($filePath, 'r');
        while (!$file->eof()) {
            $file->fgets();
            $lineCount++;
        }
        return $lineCount;
    }

    private function getLogPageLines(string $filePath, int $page, int $limit, ?string $search = null): array
    {
        $lines     = [];
        $start     = ($page - 1) * $limit;
        $readCount = 0;
        $file      = new \SplFileObject($filePath, 'r');
        $file->seek($start);

        while (!$file->eof() && $readCount < $limit) {
            $line = rtrim($file->fgets(), "\r\n");
            if ($search && stripos($line, $search) === false) {
                continue;
            }
            $lines[] = $line;
            $readCount++;
        }
        return $lines;
    }


    private function loggers(Request $request, int $limit): LengthAwarePaginator
    {
        return Logger::query()
            ->when($request->get('userid'), function ($query) use ($request) {
                $query->where('userid', $request->get('userid'));
            })
            ->when($request->get('model'), function ($query) use ($request) {
                $query->where('model', 'like', "{$request->get('model')}%");
            })
            ->when($request->get('old'), function ($query) use ($request) {
                $query->where('old', 'like', "%{$request->get('old')}%");
            })
            ->when($request->get('new'), function ($query) use ($request) {
                $query->where('new', 'like', "%{$request->get('new')}%");
            })
            ->when($request->get('created_ats'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', "%{$request->get('created_ats')}%");
            })
            ->when($request->get('created_ate'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', "%{$request->get('created_ate')}%");
            })
            ->latest('id')
            ->paginate($limit);
    }
}
