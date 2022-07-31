<?php

namespace App\Http\Controllers\Api\backend;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Validator;
use DB;

class ArtistController extends Controller
{
    const ITEM_PER_PAGE = 8;

    /**
     * addCollection
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addArtist(Request $request)
    {
        $currentUser = $request->user();
        if (
            $currentUser->hasPermission('create_artist')
        ) {
            $validation = Validator::make($request->all(), [
                'name' => 'required',
                'type' => 'required',
                'region' => 'required',
                'born_location' => 'required',
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg',
            ]);

            if ($validation->fails()) {
                return response()->json(new JsonResponse([], $validation->errors(), ''), Response::HTTP_OK);

            } else {

                $data = $request->all();
                if (isset($data['image']) && !empty($data['image'])) {
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $file = Storage::disk('public')->putFileAs(
                        'artists/' . $request->name,
                        $request->file('image'),
                        $request->user()->id . '-' . time() . '.' . $extension
                    );

                    $data['image'] = 'artists/' . $request->name . '/' . $request->user()->id . '-' . time() . '.' . $extension;
                }
                $data['author_id'] = $request->user()->id;

                DB::beginTransaction();
                $artist = Artist::make($data);
                $artist->save();
                DB::commit();

                return response()->json(new JsonResponse([], '', 'Artist created successfully'), Response::HTTP_OK);
            }

        } else {
            return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * getArtistById
     * @param Request $request
     * @param $artist_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArtistById(Request $request, $artist_id)
    {
        try {
            $artist = Artist::findOrFail($artist_id);
            return response()->json(new JsonResponse($artist, '', 'Artist details'), Response::HTTP_OK);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(new JsonResponse([], 'Artist not found'), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * getArtistByUser
     * @param Request $request
     * @param $artist_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArtistByUser(Request $request)
    {
        $currentUser = $request->user();

        $artists = Artist::where('author_id', $currentUser->id)->get();
        if ($artists) {
            return response()->json(new JsonResponse($artists, '', ''), Response::HTTP_OK);
        }
        return response()->json(new JsonResponse([], '', 'No artists found'), Response::HTTP_OK);

    }

    /**
     * deleteArtist
     * @param Request $request
     * @param $artist_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteArtist(Request $request, $artist_id)
    {
        $currentUser = $request->user();

        if ($currentUser->hasPermission('delete_artist')) {
            try {
                $artist = Artist::findorfail($artist_id)->delete();

                return response()->json(new JsonResponse([], '', 'Artist deleted successfully'), Response::HTTP_OK);

            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(new JsonResponse([], '404 not found'), Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
        }

    }

    /**
     * getAllArtists
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllArtists(Request $request)
    {
        $artists = Artist::all();
        $searchParams = $request->all();
        $artists = Artist::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $keyword = Arr::get($searchParams, 'keyword', '');


        if (!empty($keyword)) {
            $artists->where('name', 'LIKE', '%' . $keyword . '%');
            $artists->orWhere('type', 'LIKE', '%' . $keyword . '%');
        }

        return response()->json(new JsonResponse($artists->paginate($limit), '', ''), Response::HTTP_OK);
    }

    /**
     * updateArtist
     * @param Request $request
     * @param $artist_id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function updateArtist(Request $request, $artist_id)
    {
        $currentUser = $request->user();
        if (
            $currentUser->hasPermission('update_artist')
        ) {
            $validation = Validator::make($request->all(), [
                'name' => 'required',
            ]);

            if ($validation->fails()) {
                return response()->json(new JsonResponse([], $validation->errors(), ''), Response::HTTP_OK);

            } else {
                $artist = Artist::findOrFail($artist_id);

                $data = $request->all();


                if (isset($data['image']) && !empty($data['image'])) {
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $file = Storage::disk('public')->putFileAs(
                        'artists/' . $request->name . '/',
                        $request->file('image'),
                        $request->user()->id . '-' . time() . '.' . $extension
                    );

                    $data['image'] = 'artists/' . $request->name . '/' . $request->user()->id . '-' . time() . '.' . $extension;
                }

                DB::beginTransaction();

                $artist->update($data);

                DB::commit();

                return response()->json(new JsonResponse([], '', 'Artist updated successfully'), Response::HTTP_OK);
            }
            return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
        }
    }


     /**
     * getArtistsPublic
     * @param Request $request
     * @param $collection_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArtistsPublic(Request $request)
    {

        $artists = Artist::where('status','public')->get();
        if($artists){
            return response()->json(new JsonResponse($artists, '', ''), Response::HTTP_OK);
        }
        return response()->json(new JsonResponse([], '', 'No Artists found'), Response::HTTP_OK);
    }

    /**
     * get artists logs
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLogs(Request $request)
    {
        $currentUser = $request->user();
        if (
            $currentUser->hasPermission('get_artist_logs')
        ) {
            $activitys = Activity::where('log_name', 'artist')->get();

            return response()->json(new JsonResponse($activitys, '', ''), Response::HTTP_OK);
        }
        return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
    }


}
