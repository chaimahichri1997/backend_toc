<?php

namespace App\Http\Controllers\Api\backend;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\User;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Validator;
use DB;

class CollectionController extends Controller
{
    const ITEM_PER_PAGE = 15;

    /**
     * addCollection
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCollection(Request $request)
    {
        $currentUser = $request->user();
        if (
            $currentUser->hasPermission('create_collection')
        ) {
            $validation = Validator::make($request->all(), [
                'name' => 'required',
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg',
                'status' => 'required',
            ]);

            if ($validation->fails()) {
                return response()->json(new JsonResponse([], $validation->errors(), ''), Response::HTTP_OK);
            } else {

                $data = $request->all();
                if (isset($data['image']) && !empty($data['image'])) {
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $file = Storage::disk('public')->putFileAs(
                        'collections/' . $request->name,
                        $request->file('image'),
                        $request->user()->id . '-' . time() . '.' . $extension
                    );
                    $url = $file;


                    $data['image'] =  env('APP_URL') . '/' . 'storage/' . $url;
                }
                $data['author_id'] = $request->user()->id;

                DB::beginTransaction();
                $collection = Collection::make($data);
                $collection->save();
                DB::commit();

                return response()->json(new JsonResponse([], '', 'Collection created successfully'), Response::HTTP_OK);
            }
        } else {
            return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
        }
    }





    /**
     * getCollectionById
     * @param Request $request
     * @param $collection_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCollectionById(Request $request, $collection_id)
    {
        try {
            $collection = Collection::where('id', $collection_id)->with(['sub_collections', 'sub_collections'])->get();
            return response()->json(new JsonResponse($collection, '', 'Collection details'), Response::HTTP_OK);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(new JsonResponse([], 'Collection not found'), Response::HTTP_BAD_REQUEST);
        }
    }


    /**
     * getCollectionByUser
     * @param Request $request
     * @param $collection_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCollectionByUser(Request $request)
    {
        $currentUser = $request->user();

        $collection = Collection::where('author_id', $currentUser->id)->with(['sub_collections'])->get();
        if ($collection) {
            return response()->json(new JsonResponse($collection, '', ''), Response::HTTP_OK);
        }
        return response()->json(new JsonResponse([], '', 'No Collection found'), Response::HTTP_OK);
    }



    /**
     * getCollectionByUser
     * @param Request $request
     * @param $collection_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCollectionPublic(Request $request)
    {

        $collection = Collection::where('status', 'public')->get();
        if ($collection) {
            return response()->json(new JsonResponse($collection, '', ''), Response::HTTP_OK);
        }
        return response()->json(new JsonResponse([], '', 'No Collection found'), Response::HTTP_OK);
    }




    /**
     * getFavoriteCollection
     * @param Request $request
     * @param $collection_id
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     * deleteCollection
     * @param Request $request
     * @param $collection_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCollection(Request $request, $collection_id)
    {
        $currentUser = $request->user();

        if ($currentUser->hasPermission('delete_collection')) {
            try {
                $collection = Collection::findorfail($collection_id)->delete();

                return response()->json(new JsonResponse([], '', 'Collection deleted successfully'), Response::HTTP_OK);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(new JsonResponse([], '404 not found'), Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * getAllCollections
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCollections(Request $request)


    {

        $collection = Collection::all();
        $searchParams = $request->all();
        $collection = Collection::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $keyword = Arr::get($searchParams, 'keyword', '');


        if (!empty($keyword)) {
            $collection->where('name', 'LIKE', '%' . $keyword . '%');
            $collection->orWhere('tags', 'LIKE', '%' . $keyword . '%');
        }

        return response()->json(new JsonResponse($collection->paginate($limit), '', ''), Response::HTTP_OK);
    }


    /**
     * updateCollection
     * @param Request $request
     * @param $collection_id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function updateCollection(Request $request, $collection_id)
    {
        $currentUser = $request->user();
        if (
            $currentUser->hasPermission('update_collection')
        ) {
            $validation = Validator::make($request->all(), [

            ]);

            if ($validation->fails()) {
                return response()->json(new JsonResponse([], $validation->errors(), ''), Response::HTTP_OK);
            } else {
                $collection = Collection::findOrFail($collection_id);

                $data = $request->all();


                if (isset($data['image']) && !empty($data['image'])) {
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $file = Storage::disk('public')->putFileAs(
                        'collections/' . $request->name . '/',
                        $request->file('image'),
                        $request->user()->id . '-' . time() . '.' . $extension
                    );

                    $data['image'] = 'collections/' . $request->name . '/' . $request->user()->id . '-' . time() . '.' . $extension;
                }

                DB::beginTransaction();

                $collection->update($data);

                DB::commit();

                return response()->json(new JsonResponse([], '', 'Collection updated successfully'), Response::HTTP_OK);
            }
            return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * get collections logs
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLogs(Request $request)
    {
        $currentUser = $request->user();
        if (
            $currentUser->hasPermission('get_collection_logs')
        ) {
            $activitys = Activity::where('log_name', 'collection')->get();

            return response()->json(new JsonResponse($activitys, '', ''), Response::HTTP_OK);
        }
        return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
    }



    /**
     * getArtWorkById
     * @param Request $request
     * @param $artwork_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCollectionSubById(Request $request, $collection_id)
    {
        $artwork = Collection::where('id', $collection_id)->with('subcollection', 'art_works')->get();
        if (!$artwork) {
            return response()->json(new JsonResponse([], '', 'No sub found'), Response::HTTP_OK);
        }

        return response()->json(new JsonResponse($artwork, '', ''), Response::HTTP_OK);
    }
}
