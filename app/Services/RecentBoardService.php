<?php

namespace App\Services;

use App\Models\Board;
use Illuminate\Support\Collection;

class RecentBoardService
{
    private const MAX_RECENT_BOARDS = 5;

    public function push(Board $board): void
    {
        $recentBoards = collect(session('recent_boards', []));

        $recentBoards = $recentBoards
            ->reject(fn ($item) => $item['id'] === $board->id)
            ->prepend([
                'id' => $board->id,
                'name' => $board->name,
                'slug' => $board->slug,
            ])
            ->take(self::MAX_RECENT_BOARDS)
            ->values()
            ->all();

        session(['recent_boards' => $recentBoards]);
    }

    public function all(): Collection
    {
        $recentBoards = collect(session('recent_boards', []));

        if ($recentBoards->isEmpty()) {
            return collect();
        }

        $activeBoards = Board::active()
            ->whereIn('id', $recentBoards->pluck('id')->all())
            ->get(['id', 'name', 'slug'])
            ->keyBy('id');

        $filteredBoards = $recentBoards
            ->map(function (array $item) use ($activeBoards) {
                $board = $activeBoards->get($item['id']);

                if (! $board) {
                    return null;
                }

                return [
                    'id' => $board->id,
                    'name' => $board->name,
                    'slug' => $board->slug,
                ];
            })
            ->filter()
            ->values();

        session(['recent_boards' => $filteredBoards->all()]);

        return $filteredBoards;
    }
}
