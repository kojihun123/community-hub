<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PostAttachmentUploadController extends Controller
{
    public function storeImage(Request $request): JsonResponse
    {
        $request->validate([
            'upload' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ]);

        $file = $request->file('upload');
        $path = null;

        try {
            $path = $file->store('posts/attachments', 'public');
                        
            $attachment = Attachment::create([
                'user_id' => auth()->id(),
                'type' => 'image',
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'is_temporary' => true,
            ]);

            return response()->json([
                'id' => $attachment->id,
                'url' => Storage::url($path),
            ], 201);

        } catch (Throwable $e) {
            if ($path !== null && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            report($e);

            return response()->json([
                'error' => [
                    'message' => '첨부파일 업로드 중 문제가 발생했습니다.',
                ],
            ], 500);
        }
    }
}
