<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::all();
        return view('media.upload', compact('media'));
    }

    public function showMediaList()
    {
        $media = Media::all();
        return view('media.list', compact('media'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'media_file' => 'required|mimes:pdf,mp4,mov,avi|max:51200'
        ]);

        $file = $request->file('media_file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/media', $filename);

        $type = $file->getClientOriginalExtension() === 'pdf' ? 'pdf' : 'video';

        Media::create([
            'filename' => $filename,
            'type' => $type
        ]);

        return redirect()->route('media.upload.page')->with('success', ucfirst($type) . ' uploaded successfully!');
    }

    public function delete($id)
    {
        $media = Media::find($id);

        if (!$media) {
            Toastr::error('Media not found.');
            return redirect()->route('media.list');
        }

        $filePath = 'media/' . $media->filename;
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }

        $media->delete();

        Toastr::success('Media deleted successfully.');
        return redirect()->route('media.list');
    }
}
