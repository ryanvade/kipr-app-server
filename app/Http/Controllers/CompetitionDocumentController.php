<?php

namespace KIPR\Http\Controllers;

use Storage;
use KIPR\CompetitionDocument;
use Illuminate\Http\Request;
use KIPR\Http\Requests\CreateCompetitionDocumentRequest;

class CompetitionDocumentController extends Controller
{

    public function __construct() {
      $this->middleware('auth:api', [
        'only' => [
          'destroy',
          'update',
          'store',
        ]
      ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CompetitionDocument::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \KIPR\Http\Requests\CreateCompetitionDocumentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCompetitionDocumentRequest $request)
    {
        $file = $request->file('file');
        $file->store('', 'public');
        $c = CompetitionDocument::create([
          'name' => $request->name,
          'file_location' => 'public/storage/' . $file->hashName()
        ]);
        return response()->json([
          'status' => 'success',
          'document' => $c
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \KIPR\CompetitionDocument  $competitionDocument
     * @return \Illuminate\Http\Response
     */
    public function show(CompetitionDocument $document)
    {
        return $document;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \KIPR\CompetitionDocument  $competitionDocument
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompetitionDocument $competitionDocument)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \KIPR\CompetitionDocument  $competitionDocument
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompetitionDocument $document)
    {
        $document->delete();
        return response()->json([
          'status' => "success",
          'message' => 'document deleted'
        ]);
    }
}
