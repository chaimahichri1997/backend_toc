<?php

namespace App\Http\Controllers\Api\backend;

use App\Helpers\Helper;
use App\Models\BasicPage;
use App\Models\Section;
use App\Http\Controllers\Controller;
use App\Models\SectionConfiguration;
use App\Traits\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\Models\Activity;

class BasicPageController extends Controller
{

    const ITEM_PER_PAGE = 15;

    /**
     * Get list of all pages
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllPages(Request $request)
    {
        $searchParams = $request->all();
        $pages = BasicPage::query()->Published()->with('sections');
        if($pages)
        {
            $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
            $keyword = Arr::get($searchParams, 'keyword', '');


            if (!empty($keyword)) {
                $pages->where('title', 'LIKE', '%' . $keyword . '%');
                $pages->orWhere('body', 'LIKE', '%' . $keyword . '%');
            }

            return response()->json(new JsonResponse($pages->paginate($limit), '', ''), Response::HTTP_OK);
        }else{
            return response()->json(new JsonResponse([], '', 'No pages found'), Response::HTTP_OK);
        }

    }

    /**
     * Store pages
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addPage(Request $request)
    {
        $currentUser = $request->user();
        if (
            $currentUser->hasPermission('create_page')
        ) {
            $validation = Validator::make($request->all(), [
                'name' => 'required'
            ]);

            if ($validation->fails()) {
                return response()->json(new JsonResponse([], $validation->errors(), ''), Response::HTTP_OK);

            } else {

                $data = [
                    'title'=>[
                        'en'=>$request->title,
                        'fr'=>$request->title_fr
                    ],
                    'name'=>[
                        'en'=>$request->name,
                        'fr'=>$request->name_fr
                    ],
                    'description'=>[
                        'en'=>$request->description,
                        'fr'=>$request->description_fr
                    ],
                    'body'=>[
                        'en'=>$request->body,
                        'fr'=>$request->body_fr
                    ],
                    'image'=> $request->file('image')
                ];
                $title_existed = BasicPage::where('name', $data['name'])->first();
                if ($title_existed && $title_existed->exists()) {
                    return response()->json(new JsonResponse([], 'Page having same title already exists'), Response::HTTP_BAD_REQUEST);
                }

                if (isset($data['image']) && !empty($data['image'])) {
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $file = Storage::disk('public')->putFileAs(
                        'pages',
                        $request->file('image'),
                        $request->user()->id . '-' . time() . '.' . $extension
                    );

                    $data['image'] = 'pages/' . $request->user()->id . '-' . time() . '.' . $extension;
                }
                $data['author_id'] = $request->user()->id;
                $data['slug'] = Helper::URLSlug($request->name);

                DB::beginTransaction();
                $page = BasicPage::make($data);
                $page->save();
                DB::commit();

                return response()->json(new JsonResponse([], '', 'Page created successfully'), Response::HTTP_OK);
            }

        } else {
            return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Get Page By ID
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPageById(Request $request)
    {

        $page = BasicPage::where('id', $request->pageId)->first();
        $page['sections'] = Section::where('page_id', $request->pageId)->get()->groupBy('type');

        if(!$page){
            return response()->json(new JsonResponse([], 'Page not exists'), Response::HTTP_BAD_REQUEST);
        }

        return response()->json(new JsonResponse($page, '', 'Page details'), Response::HTTP_OK);
    }

    /**
     * Delete Page
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePage(Request $request)
    {
        $currentUser = $request->user();

        if ($currentUser->hasPermission('delete_page')) {
            try {
                BasicPage::findorfail($request->pageId)->delete();

                return response()->json(new JsonResponse([], '', 'Page deleted successfully'), Response::HTTP_OK);

            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(new JsonResponse([], '404 not found'), Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Update Page
     * @param Request $request
     * @return void
     */
    public function updatePage(Request $request)
    {
        $currentUser = $request->user();
        if (
            $currentUser->hasPermission('update_page')
        ) {
            // $validation = Validator::make($request->all(), [
            //     'title' => 'required',
            //     'body' => 'required',
            // ]);

            // if ($validation->fails()) {
            //     return response()->json(new JsonResponse([], $validation->errors(), ''), Response::HTTP_OK);

            // } else {

                $page = BasicPage::find($request->pageId);

                if(!$page){
                    return response()->json(new JsonResponse([], 'Page not exists'), Response::HTTP_BAD_REQUEST);
                }

                $data = $request->all();

                if (isset($data['name']) && !empty($data['name'])) {
                    $data['slug'] = Helper::URLSlug($data['name']);
                }

                if (isset($data['image']) && !empty($data['image'])) {
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $file = Storage::disk('public')->putFileAs(
                        'pages',
                        $request->file('image'),
                        $request->user()->id . '-' . time() . '.' . $extension
                    );

                    $data['image'] = 'pages/' . $request->user()->id . '-' . time() . '.' . $extension;
                }

                DB::beginTransaction();
                $page->update($data);
                DB::commit();

                return response()->json(new JsonResponse([], '', 'Page updated successfully'), Response::HTTP_OK);
            // }
        }
        return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
    }

     /**
     * Get list of all sections
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSections(Request $request)
    {
        $sections = Section::get();

        if($sections){
            return response()->json(new JsonResponse($sections, '', 'Sections list'), Response::HTTP_OK);
        }
    }

    /**
     * Get list of all sections configuration
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSectionsConfiguration(Request $request)
    {
        $sections_configuration = SectionConfiguration::get();

        if($sections_configuration){
            return response()->json(new JsonResponse($sections_configuration, '', 'Sections configuration list'), Response::HTTP_OK);
        }
    }

    /**
     * get basic pages logs
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLogs(Request $request)
    {
        $currentUser = $request->user();
        if (
            $currentUser->hasPermission('get_basic_pages_logs')
        ) {
            $activitys = Activity::where('log_name', 'Basic_Pages')->get();

            return response()->json(new JsonResponse($activitys, '', 'Basic pages logs'), Response::HTTP_OK);
        }
        return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
    }

}
