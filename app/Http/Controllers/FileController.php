<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Http\Requests\StoreFolderRequest;
use App\Http\Resources\FileResource;
use Illuminate\support\Facades\Auth;
use Inertia\Inertia;

class FileController extends Controller
{
    public function myFiles(File $folder = null)
    {
        // dd($folder);
        $folder = $this->getRoot();
        $files = File::query()
            ->where('parent_id', $folder->id)
            ->where('created_by', Auth::id())
            ->orderBy('is_folder', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);


        $files = FileResource::collection($files);

        return Inertia::render('MyFiles', compact('files'));
    }

    public function createFolder(StoreFolderRequest $request)
    {
        $data = $request->validated();
        $parent = $request->parent;

        if (!$parent) {
            $parent = $this->getRoot();
        }
        // dd($parent);

        $file = new File();
        $file->is_folder = 1;
        $file->name = $data['name'];

        $parent->appendNode($file);
    }

    private function getRoot()
    {
        return File::query()->whereIsRoot()->where('created_by', Auth::id())->firstOrFail();
    }
}
