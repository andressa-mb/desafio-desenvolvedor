<?php

namespace App\Http\Controllers\Files;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    public function index() {

    }

    /**
    * Upload doc in the system
    */
    public function store(Request $request) {
        $validated = $request->validate(
            [
                'file' => [
                    'required',
                    'file',
                    'mimetypes:text/csv,text/plain,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ]
            ],
            [
                'file.required' => 'We need a file!',
                'file.file' => 'We need a file archive!',
                'file.mimetypes' => 'We need a mimetypes valid!',
            ]
        );

        $file = $validated['file'];
        if (!in_array($file->getClientOriginalExtension(), ['csv', 'xlsx', 'xls'])) {
            return response()->json(['error' => 'Extensão inválida'], 422);
        }

        $hash = md5_file($file->getRealPath());
        if(Storage::disk('public')->exists("files/$hash")) {
            return response()->json([
                'message' => 'Arquivo já enviado anteriormente'
            ], 409);
        }

        Storage::disk('public')->putFileAs('files', $file, $hash);

        $this->user()->files()->create([
            'original_name' => $file->getClientOriginalName(),
            'path' => 'files/' . $hash,
            'hash_name' => $hash,
            'extension' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
        ]);

        return response()->json([
            'message' => 'Arquivo enviado com sucesso.'
        ]);
    }

    /**
    * History of docs in the system
    */
    public function history(Request $request) {
        $query = File::query();

        if($request->filled('name')) {
            $query->where('original_name', 'like', "%$request->name%");
        }

        if($request->filled('date')) {
            $query->whereDate('created_at', '>=', $request->date);
        }

        return response()->json([
            'files' => $query->orderBy('created_at')->get()
        ]);
    }

    ///search/{text}
    public function search(Request $request, $text) {

    }

    public function show() {

    }

    public function update() {

    }

    public function destroy() {

    }
}
