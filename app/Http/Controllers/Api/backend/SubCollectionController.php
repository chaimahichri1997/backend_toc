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

class SubCollectionController extends Controller
{

    const ITEM_PER_PAGE = 8;
    /**
     * addsub
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addsubCollection(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'image' => 'required|file|mimes:png,jpg',
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
                    'subcollection',
                    $data['image'],
                    $request->user()->id . '-' . time() . '.' . $extension
                );

                $url = $storage;

                $data['image'] =  env('APP_URL') . '/' . 'storage/' . $url;
            }
            $data['author_id'] = $request->user()->id;
            $data['collection_id'] = 2;


            DB::beginTransaction();
            $subcollection = SubCollection::make($data);
            $subcollection->save();
            DB::commit();

            return response()->json(new JsonResponse([], '', 'subCollection created successfully'), Response::HTTP_OK);
        }
    }


    /**
     * getSubCollectionById
     * @param Request $request
     * @param $subCollection_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubCollectionbyid(Request $request, $subCollection_id)
    {
        try {
            $subcol = SubCollection::where('id', $subCollection_id)->with(['art_works'])->first();
            return response()->json(new JsonResponse($subcol, '', 'sub col details'), Response::HTTP_OK);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(new JsonResponse([], 'Collection not found'), Response::HTTP_BAD_REQUEST);
        }
    }
}
