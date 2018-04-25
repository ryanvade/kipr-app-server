<!-- Copyright (c) 2018 KISS Institute for Practical Robotics

BSD v3 License

All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this
  list of conditions and the following disclaimer.

* Redistributions in binary form must reproduce the above copyright notice,
  this list of conditions and the following disclaimer in the documentation
  and/or other materials provided with the distribution.

* Neither the name of KIPR Scoring App nor the names of its
  contributors may be used to endorse or promote products derived from
  this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. -->
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
