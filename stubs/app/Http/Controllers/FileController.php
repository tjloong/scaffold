<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Requests\FileStoreRequest;

class FileController extends Controller
{
    /**
     * File list
     *
     * @return Response
     */
    public function list()
    {
        $files = File::fetch();

        if (request()->isMethod('post')) return back()->with('options', $files);

        return inertia('settings/file/list', [
            'files' => $files,
        ]);
    }

    /**
     * Upload fild
     * 
     * @return Response
     */
    public function upload()
    {
        $files = File::upload();

        return back()->with('toast', 'Files Saved::success');
    }

    /**
     * Store file
     *
     * @param FileStoreRequest $request
     * @return Response
     */
    public function store(FileStoreRequest $request)
    {
        $request->validated();

        $file = File::findOrFail($request->id);

        $file->fill($request->input('file'))->save();

        return back()->with('toast', 'File Updated::success');
    }

    /**
     * Delete role
     *
     * @return Response
     */
    public function delete()
    {
        File::whereIn('id', explode(',', request()->id))->get()->each(function ($file) {
            $file->delete();
        });

        return redirect()->route('file.list')->with('toast', 'Files Deleted');
    }
}
