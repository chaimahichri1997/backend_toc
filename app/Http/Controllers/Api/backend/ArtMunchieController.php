<?php

namespace App\Http\Controllers\Api\backend;

use App\Http\Controllers\Controller;
use App\Models\ArtMunchie;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Validator;
use DB;

class ArtMunchieController extends Controller
{
    const ITEM_PER_PAGE = 15;

    /**
     * addArtMunchie
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addArtMunchie(Request $request)
    {
        $currentUser = $request->user();
        if (
            $currentUser->hasPermission('create_artMunchie')
        ) {
            $validation = Validator::make($request->all(), [
                'title' => 'required',
                'image' => 'required',
                'url' => 'required|url',
                'body' => 'required',
                'date' => 'required',
                'category' => 'required',
                'region' => 'required',
            ]);

            if ($validation->fails()) {
                return response()->json(new JsonResponse([], $validation->errors(), ''), Response::HTTP_OK);

            } else {

                $data = [
                  'title'=>[
                      'en'=>$request->title,
                      'fr'=>$request->title_fr
                  ],
                    'body'=>[
                        'en'=>$request->body,
                        'fr'=>$request->body_fr
                    ],
                    'url'=>$request->url,
                    'image'=>$request->file('image')
                ];

                if (isset($data['image']) && !empty($data['image'])) {
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $file = Storage::disk('public')->putFileAs(
                        'artMunchies/' . $request->title,
                        $request->file('image'),
                        $request->user()->id . '-' . time() . '.' . $extension
                    );

                    $data['image'] = 'artMunchies/' . $request->title . '/' . $request->user()->id . '-' . time() . '.' . $extension;
                }
                $data['author_id'] = $request->user()->id;

                DB::beginTransaction();
                $artMunchie = ArtMunchie::create($data);
                DB::commit();

                return response()->json(new JsonResponse([], '', 'Art Munchie created successfully'), Response::HTTP_OK);
            }

        } else {
            return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * getArtMunchieById
     * @param Request $request
     * @param $collection_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArtMunchieById(Request $request, $artMunchie_id)
    {
        try {
            $artMunchie = ArtMunchie::findOrFail($artMunchie_id);
            return response()->json(new JsonResponse($artMunchie, '', 'Art Munchie details'), Response::HTTP_OK);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(new JsonResponse([], 'Art Munchie not found'), Response::HTTP_BAD_REQUEST);
        }

    }

    /**
     * getArtMunchiesByUser
     * @param Request $request
     * @param $collection_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArtMunchiesByUser(Request $request)
    {
        $currentUser = $request->user();

        $artMunchies = ArtMunchie::where('author_id',$currentUser->id)->get();
        if($artMunchies){
            return response()->json(new JsonResponse($artMunchies, '', ''), Response::HTTP_OK);
        }
        return response()->json(new JsonResponse([], '', 'No art munchies found'), Response::HTTP_OK);

    }


    /**
     * deleteArtMunchie
     * @param Request $request
     * @param $collection_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteArtMunchie(Request $request, $artMunchie_id)
    {
        $currentUser = $request->user();

        if ($currentUser->hasPermission('delete_artMunchie')) {
            try {
                $artMunchie = ArtMunchie::findorfail($artMunchie_id)->delete();

                return response()->json(new JsonResponse([], '', 'Art Munchie deleted successfully'), Response::HTTP_OK);

            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(new JsonResponse([], '404 not found'), Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
        }

    }

    /**
     * getAllArtMunchies
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllArtMunchies(Request $request)
    {
        //$artMunchies = ArtMunchie::all();
        $searchParams = $request->all();
        $artMunchies = ArtMunchie::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $keyword = Arr::get($searchParams, 'keyword', '');


        if (!empty($keyword)) {
            $artMunchies->where('title', 'LIKE', '%' . $keyword . '%');
            $artMunchies->orWhere('body', 'LIKE', '%' . $keyword . '%');
            $artMunchies->orWhere('category', 'LIKE', '%' . $keyword . '%');
            $artMunchies->orWhere('region', 'LIKE', '%' . $keyword . '%');
        }

        return response()->json(new JsonResponse($artMunchies->paginate($limit), '', ''), Response::HTTP_OK);
    }

    /**
     * updateArtMunchie
     * @param Request $request
     * @param $artMunchie_id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function updateArtMunchie(Request $request, $artMunchie_id)
    {
        $currentUser = $request->user();
        if (
            $currentUser->hasPermission('update_artMunchie')
        ) {
            $validation = Validator::make($request->all(), [
                'title' => 'required',
            ]);

            if ($validation->fails()) {
                return response()->json(new JsonResponse([], $validation->errors(), ''), Response::HTTP_OK);

            } else {
                $artMunchie = ArtMunchie::findOrFail($artMunchie_id);

                $data = $request->all();


                if (isset($data['image']) && !empty($data['image'])) {
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $file = Storage::disk('public')->putFileAs(
                        'artMunchies/' . $request->title . '/',
                        $request->file('image'),
                        $request->user()->id . '-' . time() . '.' . $extension
                    );

                    $data['image'] = 'artMunchies/' . $request->title . '/' . $request->user()->id . '-' . time() . '.' . $extension;
                }

                DB::beginTransaction();

                $artMunchie->update($data);

                DB::commit();

                return response()->json(new JsonResponse([], '', 'Art Munchie updated successfully'), Response::HTTP_OK);
            }
            return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * get munchies logs
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLogs(Request $request)
    {
        $currentUser = $request->user();
        if (
            $currentUser->hasPermission('get_artMunchie_logs')
        ) {
            $activitys = Activity::where('log_name', 'Art_Munchies')->get();

            return response()->json(new JsonResponse($activitys, '', ''), Response::HTTP_OK);
        }
        return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
    }

   
}
