<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::where('client_id', session('client_id'))
            ->where('is_active', true)
            ->orderByDesc('uploaded_at')
            ->get();
        return view('client.media.index', compact('media'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,webp,svg,mp4,pdf',
            'alt_text' => 'nullable|string|max:255',
            'usage_key' => 'nullable|string|max:100',
        ]);

        $file = $request->file('file');
        $clientId = session('client_id');

        // Store in public/uploads/{client_id}/
        $dir = 'uploads/' . $clientId;
        $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                    . '.' . $file->getClientOriginalExtension();

        $file->move(public_path($dir), $fileName);

        $mimeType = $file->getClientMimeType();
        $mediaType = 'image';
        if (str_starts_with($mimeType, 'video/')) {
            $mediaType = 'video';
        } elseif ($mimeType === 'application/pdf') {
            $mediaType = 'document';
        }

        $media = Media::create([
            'client_id' => $clientId,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $clientId . '/' . $fileName,
            'alt_text' => $request->alt_text,
            'media_type' => $mediaType,
            'mime_type' => $mimeType,
            'file_size' => filesize(public_path($dir . '/' . $fileName)),
            'usage_key' => $request->usage_key,
            'is_active' => true,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'media' => [
                    'id' => $media->id,
                    'file_name' => $media->file_name,
                    'url' => $media->url,
                    'media_type' => $media->media_type,
                ],
            ]);
        }

        return redirect()->route('client.media.index')->with('success', 'Bestand geupload');
    }

    public function update(Request $request, Media $medium)
    {
        if ($medium->client_id != session('client_id')) {
            abort(403);
        }

        $request->validate([
            'alt_text' => 'nullable|string|max:255',
        ]);

        $medium->update(['alt_text' => $request->alt_text]);
        return back()->with('success', 'Alt tekst bijgewerkt');
    }

    public function destroy(Media $medium)
    {
        if ($medium->client_id != session('client_id')) {
            abort(403);
        }

        // Delete physical file
        $filePath = public_path('uploads/' . $medium->file_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $medium->update(['is_active' => false]);
        return redirect()->route('client.media.index')->with('success', 'Bestand verwijderd');
    }

    // JSON endpoint for media picker in product/portfolio forms
    public function json()
    {
        $media = Media::where('client_id', session('client_id'))
            ->where('is_active', true)
            ->where('media_type', 'image')
            ->orderByDesc('uploaded_at')
            ->get()
            ->map(fn($m) => [
                'id' => $m->id,
                'file_name' => $m->file_name,
                'url' => $m->url,
                'alt_text' => $m->alt_text,
            ]);

        return response()->json($media);
    }
}
