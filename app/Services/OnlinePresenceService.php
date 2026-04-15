<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Redis;

class OnlinePresenceService
{
    private const HEARTBEAT_TTL = 60;
    private const USERS_KEY = 'presence:home:users';
    private const GUESTS_KEY = 'presence:home:guests';

    public function touch(?User $user, string $sessionId): array
    {
        $now = now()->timestamp;
        $expiresBefore = $now - self::HEARTBEAT_TTL;

        if ($user) {
            Redis::zadd(self::USERS_KEY, $now, (string) $user->id);
            Redis::zrem(self::GUESTS_KEY, $sessionId);
        } else {
            Redis::zadd(self::GUESTS_KEY, $now, $sessionId);
        }

        return $this->counts($expiresBefore);
    }

    public function current(): array
    {
        return $this->counts(now()->timestamp - self::HEARTBEAT_TTL);
    }

    private function counts(int $expiresBefore): array
    {
        Redis::zremrangebyscore(self::USERS_KEY, '-inf', $expiresBefore);
        Redis::zremrangebyscore(self::GUESTS_KEY, '-inf', $expiresBefore);

        $users = (int) Redis::zcard(self::USERS_KEY);
        $guests = (int) Redis::zcard(self::GUESTS_KEY);

        return [
            'users' => $users,
            'guests' => $guests,
            'total' => $users + $guests,
        ];
    }
}
