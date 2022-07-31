<?php

namespace App\Http\Controllers\Api\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ArtWork;
use App\Models\Collection;
use App\Traits\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
use App\Models\CollectionArtwork;
use App\Models\Document;
use App\Models\SubCollection;
use PDF;

class DocumentController extends Controller
{

    const ITEM_PER_PAGE = 8;



    /**
     * addDocument
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addDocument(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'image' => 'required|file|mimes:pdf,docx',
            'title' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json(new JsonResponse([], $validation->errors(), ''), Response::HTTP_OK);
        } else {

            $data = $request->all();
            if (isset($data['image']) && !empty($data['image'])) {
                $extension = $data['image']->getClientOriginalExtension();
                $filename = $data['image']->getClientOriginalName();
                $storage = Storage::disk('public')->putFileAs(
                    'documents',
                    $data['image'],
                    $request->user()->id . '-' . time() . '.' . $extension
                );

                $url = $storage;

                $data['image'] =  env('APP_URL') . '/' . 'storage/' . $url;
            }
            $data['author_id'] = $request->user()->id;

            DB::beginTransaction();
            $document = Document::make($data);
            $document->save();
            DB::commit();

            return response()->json(new JsonResponse([], '', 'document created successfully'), Response::HTTP_OK);
        }
    }


    /**
     * getArtWorkByArtist
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */


    /**
     * getDocumentByArtwork
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDocumentByArtwork(Request $request, $artwork_id)
    {
        $documents = Document::where('artwork_id', $artwork_id)->get();
        if ($documents) {
            return response()->json(new JsonResponse($documents, '', 'List of docccc found'), Response::HTTP_OK);
        }
        return response()->json(new JsonResponse([], '', 'No docccc found'), Response::HTTP_OK);
    }

    public function generatePDF()
    {
        $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'date' => date('m/d/Y')
        ];

        $pdf = PDF::loadView('myPDF', $data);

        return $pdf->download('itsolutionstuff.pdf');
    }


}
