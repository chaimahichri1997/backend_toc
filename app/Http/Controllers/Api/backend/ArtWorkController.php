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


class ArtWorkController extends Controller
{

    const ITEM_PER_PAGE = 8;

    /**
     * getAllArtWorks
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllArtWorks(Request $request)
    {
        $currentUser = $request->user();
        if (
            $currentUser->hasPermission('list_art_works')
        ) {
            $searchParams = $request->all();
            $artworks = ArtWork::query();
            $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
            $keyword = Arr::get($searchParams, 'keyword', '');


            if (!empty($keyword)) {
                $artworks->where('title', 'LIKE', '%' . $keyword . '%');
                $artworks->orWhere('category', 'LIKE', '%' . $keyword . '%');
            }

            return response()->json(new JsonResponse($artworks->paginate($limit), '', 'List of ArtWorks'), Response::HTTP_OK);
        } else {
            return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * addArtWork
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addArtWork(Request $request)
    {
        $currentUser = $request->user();
        if (
            $currentUser->hasPermission('create_art_work')
        ) {
            $validation = Validator::make($request->all(), [
                'title' => 'required',
                'category' => 'required',
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg',
                'InExplore' => "false"

            ]);

            if ($validation->fails()) {
                return response()->json(new JsonResponse([], $validation->errors(), ''), Response::HTTP_OK);
            } else {

                $data = $request->all();
                if (isset($data['image']) && !empty($data['image'])) {
                    $extension = $data['image']->getClientOriginalExtension();
                    $filename = $data['image']->getClientOriginalName();
                    $storage = Storage::disk('public')->putFileAs(
                        'artworks',
                        $data['image'],
                        $request->user()->id . '-' . time() . '.' . $extension
                    );

                    $url = $storage;

                    $data['image'] =  env('APP_URL') . '/' . 'storage/' . $url;
                    $data['InExplore'] = "false";
                }
                $data['author_id'] = $request->user()->id;

                DB::beginTransaction();
                $artwork = ArtWork::make($data);
                $artwork->save();
                DB::commit();

                return response()->json(new JsonResponse([], '', 'ArtWork created successfully'), Response::HTTP_OK);
            }
        } else {
            return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * getArtWorkById
     * @param Request $request
     * @param $artwork_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArtWorkById(Request $request, $artwork_id)
    {
        $artwork = ArtWork::where('id', $artwork_id)->with('documents')->get();
        if (!$artwork) {
            return response()->json(new JsonResponse([], '', 'No artwork found'), Response::HTTP_OK);
        }

        return response()->json(new JsonResponse($artwork, '', ''), Response::HTTP_OK);
    }




    /**
     * getArtWorkByArtist
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArtWorkByArtist(Request $request, $artist_id)
    {
        $artworks = ArtWork::where('artist_id', $artist_id)->get();
        if ($artworks) {
            return response()->json(new JsonResponse($artworks, '', 'List of artworks found'), Response::HTTP_OK);
        }
        return response()->json(new JsonResponse([], '', 'No artworks found'), Response::HTTP_OK);
    }

    /**
     * updateArtWork
     * @param Request $request
     * @param $artwork_id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function updateArtWork(Request $request, $artwork_id)
    {
        $currentUser = $request->user();
        if (
            $currentUser->hasPermission('update_art_work')
        ) {
            $validation = Validator::make($request->all(), [
                'title' => 'required',
            ]);

            if ($validation->fails()) {
                return response()->json(new JsonResponse([], $validation->errors(), ''), Response::HTTP_OK);
            } else {

                $artwork = ArtWork::findOrFail($artwork_id);

                if (!$artwork) {
                    return response()->json(new JsonResponse([], 'artwork not found'), Response::HTTP_BAD_REQUEST);
                }

                $data = $request->all();

                if (isset($data['image']) && !empty($data['image'])) {
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $file = Storage::disk('public')->putFileAs(
                        'artworks/' . $request->name . '/',
                        $request->file('image'),
                        $request->user()->id . '-' . time() . '.' . $extension
                    );

                    $data['image'] = 'artworks/' . $request->name . '/' . $request->user()->id . '-' . time() . '.' . $extension;
                }

                DB::beginTransaction();

                $artwork->update($data);

                DB::commit();

                return response()->json(new JsonResponse([], '', 'Artwork updated successfully'), Response::HTTP_OK);
            }
        }
        return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
    }




    /**
     * getArtworksBasket
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArtworksBasket(Request $request)
    {

        $artwork = ArtWork::where('InBasket', "true")->get();
        if ($artwork) {
            return response()->json(new JsonResponse($artwork, '', ''), Response::HTTP_OK);
        }
        return response()->json(new JsonResponse([], '', 'No Artwork found'), Response::HTTP_OK);
    }


    /**
     * getArtworksExplore
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArtworksExplore(Request $request)
    {

        $artwork = ArtWork::where('InExplore', "true")->get();
        if ($artwork) {
            return response()->json(new JsonResponse($artwork, '', ''), Response::HTTP_OK);
        }
        return response()->json(new JsonResponse([], '', 'No explore found'), Response::HTTP_OK);
    }


    /**
     * AddArtWorktoExplore
     * @param Request $request
     * @param $artwork_id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function AddArtWorktoExplore(Request $request, $artwork_id)
    {
        $currentUser = $request->user();
        if (
            $currentUser->hasPermission('add_artwork_to_explore')
        ) {
            $validation = Validator::make($request->all(), [
                'InExplore' => 'required',
            ]);

            if ($validation->fails()) {
                return response()->json(new JsonResponse([], $validation->errors(), ''), Response::HTTP_OK);
            } else {

                $artwork = ArtWork::findOrFail($artwork_id);

                if (!$artwork) {
                    return response()->json(new JsonResponse([], 'artwork not found'), Response::HTTP_BAD_REQUEST);
                }

                $data = $request->all();

                if (isset($data['image']) && !empty($data['image'])) {
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $file = Storage::disk('public')->putFileAs(
                        'artworks/' . $request->name . '/',
                        $request->file('image'),
                        $request->user()->id . '-' . time() . '.' . $extension
                    );

                    $data['image'] = 'artworks/' . $request->name . '/' . $request->user()->id . '-' . time() . '.' . $extension;
                }

                DB::beginTransaction();

                $artwork->update($data);

                DB::commit();

                return response()->json(new JsonResponse([], '', 'Artwork In Explore successfully'), Response::HTTP_OK);
            }
        }
        return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
    }

    /**
     * deleteArtWork
     * @param Request $request
     * @param $artwork_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteArtWork(Request $request, $artwork_id)
    {
        $currentUser = $request->user();

        if ($currentUser->hasPermission('delete_art_work')) {
            try {
                $artwork = ArtWork::findorfail($artwork_id)->delete();

                return response()->json(new JsonResponse([], '', 'ArtWork deleted successfully'), Response::HTTP_OK);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(new JsonResponse([], 'ArtWork not found'), Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * restoreArtWork
     * @param Request $request
     * @param $artwork_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restoreArtWork(Request $request, $artwork_id)
    {
        $currentUser = $request->user();

        if ($currentUser->hasPermission('restore_art_work')) {

            $artwork = ArtWork::withTrashed()->where('id', $artwork_id)->first();

            if ($artwork) {
                $artwork->restore();
                return response()->json(new JsonResponse($artwork, '', 'Artwork restored successfully'), Response::HTTP_OK);
            } else {
                return response()->json(new JsonResponse([], '', 'No artwork found'), Response::HTTP_OK);
            }
        } else {
            return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * getLogs
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLogs(Request $request)
    {
        $currentUser = $request->user();
        if (
            $currentUser->hasPermission('get_artwork_logs')
        ) {
            $activitys = Activity::where('log_name', 'Artwork')->get();

            return response()->json(new JsonResponse($activitys, '', 'Artwork logs'), Response::HTTP_OK);
        }
        return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
    }

    /**
     * copyArtWork
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function copyArtWorkToCollection(Request $request, $artwork_id, $collection_id)
    {
        $artwork = ArtWork::where('id', $artwork_id)->with('collection')->first();

        if (!$artwork) {
            return response()->json(new JsonResponse([], '', 'No artwork found'), Response::HTTP_OK);
        }

        $collection = Collection::where('id', $collection_id)->first();

        if (!$collection) {
            return response()->json(new JsonResponse([], '', 'No collection found'), Response::HTTP_OK);
        }

        CollectionArtwork::updateOrCreate(['collection_id' => $collection_id, 'artwork_id' => $artwork_id]);

        return response()->json(new JsonResponse($artwork, 'artwork copied successfully'), Response::HTTP_OK);
    }
}
