<?php

declare(strict_types=1);

namespace Swoole\WebSocket {
    class Frame
    {
        public int $fd;
        public string $data;
    }

    class Server
    {
        public string $host;
        public int $port;

        public function __construct(string $host = '0.0.0.0', int $port = 0)
        {
        }

        public function set(array $settings): bool
        {
        }

        public function on(string $event, callable $callback): bool
        {
        }

        public function start(): bool
        {
        }

        public function isEstablished(int $fd): bool
        {
        }

        public function push(int $fd, string $data): bool
        {
        }

        public function getWorkerId(): int|false
        {
        }
    }
}

namespace Swoole\Http {
    class Request
    {
        public int $fd;
        public array $server;
    }

    class Response
    {
        public function end(string $content = ''): bool
        {
        }
    }
}

namespace {
    const SWOOLE_LOG_WARNING = 2;
}
