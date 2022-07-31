<?php

namespace App\Http\Controllers\Api\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use App\Models\CulturalIncubator;
use App\Models\CulturalIncubatorRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\Models\Activity;


class CulturalIncubatorController extends Controller
{

    const ITEM_PER_PAGE = 15;

    /**
     * Get list of all cultural incubators
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCulturalIncubators(Request $request)
    {
        $currentUser = $request->user();

        if ($currentUser->hasPermission('list_cultural_incubators')) {

            $searchParams = $request->all();
            $listCulturalIncubator = CulturalIncubator::query();
            $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
            $keyword = Arr::get($searchParams, 'keyword', '');

            if (!empty($keyword)) {
                $listCulturalIncubator->where('title', 'LIKE', '%' . $keyword . '%');
            }

            return response()->json(new JsonResponse($listCulturalIncubator->paginate($limit), '', ''), Response::HTTP_OK);

        } else {
            return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
        }
    }

     /**
     * Get CulturalIncubator By ID
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCulturalIncubatorById(Request $request)
    {
        $CulturalIncubator = CulturalIncubator::where('id', $request->culturalId)->first();

        if(!$CulturalIncubator){
            return response()->json(new JsonResponse([], 'Cultural incubator not found'), Response::HTTP_BAD_REQUEST);
        }

        return response()->json(new JsonResponse($CulturalIncubator, '', 'Cultural incubator details'), Response::HTTP_OK);
    }

      /**
     * Delete CulturalIncubator
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCulturalIncubator(Request $request)
    {
        $currentUser = $request->user();

        if ($currentUser->hasPermission('delete_cultural_incubator')) {
            try {
                CulturalIncubator::findorfail($request->culturalId)->delete();

                return response()->json(new JsonResponse([], '', 'Cultural incubator deleted successfully'), Response::HTTP_OK);

            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(new JsonResponse([], 'Cultural incubator not found'), Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Store cultural incubator
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCulturalIncubator(Request $request)
    {
        $currentUser = $request->user();
        if (
            $currentUser->hasPermission('create_cultural_incubator')
        ) {
            $validation = Validator::make($request->all(), [
                'title' => 'required'
            ]);

            if ($validation->fails()) {
                return response()->json(new JsonResponse([], $validation->errors(), ''), Response::HTTP_OK);

            } else {

                $data = $request->except('images');

                $title_existed = CulturalIncubator::where('title', $data['title'])->first();
                if ($title_existed && $title_existed->exists()) {
                    return response()->json(new JsonResponse([], 'Cultural incubator having same title already exists'), Response::HTTP_BAD_REQUEST);
                }

                if($request->hasFile('images'))
                {
                    foreach($request->file('images') as $file)
                    {
                        $extension = $file->getClientOriginalExtension();
                        $filename = $file->getClientOriginalName();
                        $storage = Storage::disk('public')->putFileAs(
                            'cultural-incubators',
                            $file,
                            $request->user()->id . '-' . time() . '.' . $extension
                        );

                        $url = $storage;

                        $data['images'][] =  env('APP_URL') . '/' . 'storage/' . $url;
                    }
                }

                DB::beginTransaction();
                $cultural_incubator = CulturalIncubator::make($data);
                $cultural_incubator->save();
                DB::commit();

                return response()->json(new JsonResponse([], '', 'Cultural Incubator created successfully'), Response::HTTP_OK);
            }

        } else {
            return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Update cultural incubator
     * @param Request $request
     * @return void
     */
    public function updateCulturalIncubator(Request $request)
    {
        $currentUser = $request->user();
        if (
            $currentUser->hasPermission('update_cultural_incubator')
        ) {
            // $validation = Validator::make($request->all(), [
            //     'title' => 'required',
            // ]);

            // if ($validation->fails()) {
            //     return response()->json(new JsonResponse([], $validation->errors(), ''), Response::HTTP_OK);

            // } else {

                $cultural_incubator = CulturalIncubator::find($request->culturalId);

                if(!$cultural_incubator){
                    return response()->json(new JsonResponse([], 'cultural incubator not exists'), Response::HTTP_BAD_REQUEST);
                }

                $data = $request->except('images');

                if($request->hasFile('images'))
                {
                    foreach($request->file('images') as $file)
                    {
                        $extension = $file->getClientOriginalExtension();
                        $filename = $file->getClientOriginalName();
                        $storage = Storage::disk('public')->putFileAs(
                            'cultural-incubators',
                            $file,
                            $request->user()->id . '-' . time() . '.' . $extension
                        );

                        $url = $storage;

                        $data['images'][] =  env('APP_URL') . '/' . 'storage/' . $url;
                    }
                }

                DB::beginTransaction();
                $cultural_incubator->update($data);
                DB::commit();

                return response()->json(new JsonResponse([], '', 'Cultural incubator updated successfully'), Response::HTTP_OK);
            // }
        }
        return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
    }

    /**
     * Get list of cultural incubators requests
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCulturalIncubatorsRequests(Request $request)
    {
        $currentUser = $request->user();

        if ($currentUser->hasPermission('list_cultural_incubators_requests')) {

            $searchParams = $request->all();
            $list = CulturalIncubatorRequest::query();
            $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
            $keyword = Arr::get($searchParams, 'keyword', '');

            if (!empty($keyword)) {
                $list->where('project_name', 'LIKE', '%' . $keyword . '%');
            }

            return response()->json(new JsonResponse($list->paginate($limit), '', ''), Response::HTTP_OK);

        } else {
            return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
        }
    }

//cultural incubator public space
    /**
     * getCulturalIncubator
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCulturalIncubatorPublic(Request $request)

    
    {
        $cultural_incubator = CulturalIncubator::all();
        $searchParams = $request->all();
        $cultural_incubator = CulturalIncubator::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $keyword = Arr::get($searchParams, 'keyword', '');


        if (!empty($keyword)) {
            $cultural_incubator->where('name', 'LIKE', '%' . $keyword . '%');
            $cultural_incubator->orWhere('tags', 'LIKE', '%' . $keyword . '%');
        }

        return response()->json(new JsonResponse($cultural_incubator->paginate($limit), '', ''), Response::HTTP_OK);
    }

    /**
     * Get cultural incubator request by Id
     * @param Request $request
     * @return void
     */
    public function getCulturalIncubatorRequestById(Request $request)
    {
        $request = CulturalIncubatorRequest::where('id', $request->culturalRequestId)->first();
        if(!$request){
            return response()->json(new JsonResponse([], 'Cultural incubator request not found'), Response::HTTP_BAD_REQUEST);
        }
        return response()->json(new JsonResponse($request, '', 'Cultural incubator request details'), Response::HTTP_OK);
    }

    /**
     * delete cultural incubator request
     * @param Request $request
     * @return void
     */
    public function deleteCulturalIncubatorRequest(Request $request)
    {
        $currentUser = $request->user();
        if ($currentUser->hasPermission('delete_cultural_incubator_request')) {
            try {
                CulturalIncubatorRequest::findorfail($request->culturalRequestId)->delete();
                return response()->json(new JsonResponse([], '', 'Cultural incubator request deleted successfully'), Response::HTTP_OK);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(new JsonResponse([], 'Cultural incubator request not found'), Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * get cultural incubator logs
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLogs(Request $request)
    {
        $currentUser = $request->user();
        if (
            $currentUser->hasPermission('get_cultural_incubator_logs')
        ) {
            $logs = Activity::where('log_name', 'Cultural_Incubator')->get();

            return response()->json(new JsonResponse($logs, '', 'Cultural Incubator logs'), Response::HTTP_OK);
        }
        return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
    }

}
