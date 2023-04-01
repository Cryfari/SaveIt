<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\User;

class DocController extends Controller{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * add a new document.
     *
     * @param  Request  $request
     * @return Response
     */
    public function addDocument (Request $request) {
        //add validation for file
        $this->validate($request, [
            'judul' => 'required',
            'deskripsi' => 'required',
            'file' => 'required',
            'token' => 'required',
        ]);

        $user_id = User::where('token', $request->token)->first()->id;

        $document = Document::create([
            'id' => \Ramsey\Uuid\Uuid::uuid4(),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file' => $request->file,
            'user_id' => $user_id,
        ]);
        $document->save();

        return response()->json(['message' => 'Document created successfully'], 201);
    }
    /**
     * get all documents.
     *
     * @param  Request  $request
     * @return Response
     */
    public function getDocuments () {
        $documents = Document::all();
        return response()->json(['documents' => $documents], 200);
    }

    /**
     * get a documents.
     *
     * @param  Request  $request
     * @return Response
     */

    public function getaDocument ($id) {
        $document = Document::where('id', $id)->first();
        if (!$document) {
            return response()->json(['message' => 'Document not found'], 404);
        }
        return response()->json(['document' => $document], 200);
    }
    /**
     * delete a document.
     *
     * @param  Request  $request
     * @return Response
     */
    public function deleteDocument (Request $request) {
        $this->validate($request, [
            'id' => 'required',
        ]);
        $document = Document::where('id', $request->id)->first();
        if (!$document) {
            return response()->json(['message' => 'Document not found'], 404);
        }
        $document->delete();
        return response()->json(['message' => 'Document deleted successfully'], 200);
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            // do something with the uploaded file
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
}
