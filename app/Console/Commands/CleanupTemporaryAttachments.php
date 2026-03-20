<?php

namespace App\Console\Commands;

use App\Models\Attachment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupTemporaryAttachments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attachments:cleanup-temporary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '오래된 임시 첨부파일을 정리합니다.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $attachments = Attachment::where('is_temporary', true)
            ->whereNull('post_id')
            ->where('created_at', '<=', now()->subDay())
            ->get(['id', 'path']);

        foreach($attachments as $attachment)
        {
            Storage::disk('public')->delete($attachment->path);
            $attachment->delete();
        }
    }
}
