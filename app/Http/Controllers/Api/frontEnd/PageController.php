<?php

namespace App\Http\Controllers\Api\frontEnd;

use App\Jobs\fetchInstagramData;
use App\Models\Contact;
use App\Models\InstagramData;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BasicPage;
use App\Models\Menu;
use App\Models\Section;
use App\Models\CulturalIncubator;
use App\Models\CulturalIncubatorRequest;
use App\Traits\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Phpfastcache\Helper\Psr16Adapter;
use App\Http\Controllers\Api\MailController;
use App\Models\ArtMunchie;

class PageController extends Controller
{
    /**
     * Get Page By Slug
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPageBySlug(Request $request)
    {
        $page = BasicPage::published()->where('slug', $request->slug)->first();
        $cultural_incubator = CulturalIncubator::all();
        $active_cultural_incubators = CulturalIncubator::Active()->get();
        $art_munchies = ArtMunchie::all();

        if($active_cultural_incubators->count() > 0 && $request->slug == 'home'){
            $page['sections']['cultural_incubators'] = $active_cultural_incubators;
        } else if ($active_cultural_incubators->count() > 0 && $request->slug == 'cultural-incubator'){
            $page['sections'] = Section::where('page_id', $page->id)->get()->groupBy('type');
            $page['cultural_incubators'] = $active_cultural_incubators;
        } else if($request->slug == 'home'){
            $page['sections'] = Section::where('page_id', $page->id)->get()->groupBy('type');
            $page['sections']['instagrams'] = InstagramData::all();
            $page['sections']['cultural_incubators'] = $cultural_incubator;
        } else if($request->slug == 'art-munchies'){
            $page['sections'] = Section::where('page_id', $page->id)->get()->groupBy('type');
            $page['sections']['art_munchies'] = $art_munchies;
        } else if ($page){
            $page['sections'] = Section::where('page_id', $page->id)->get()->groupBy('type');
        }else{
            return response()->json(new JsonResponse([], '', 'No Page found'), Response::HTTP_OK);
        }
        //fetchInstagramData::dispatch();

        return response()->json(new JsonResponse($page, '', 'Page details'), Response::HTTP_OK);
    }

    /**
     * Get Page By Slug
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMenuList(Request $request)
    {
        $menu = Menu::whereNull('parent_id')->orderBy('order')->with('sub_menu')->get();
        if(!$menu){
            return response()->json(new JsonResponse([], 'Menu not exists'), Response::HTTP_BAD_REQUEST);
        }

        return response()->json(new JsonResponse($menu, '', 'Menu list'), Response::HTTP_OK);
    }

    /**
     * Get Cultural incubator By Slug
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCulturalIncubatorBySlug(Request $request)
    {
        $cultural_incubator = CulturalIncubator::where('slug', $request->slug)->first();

        if(!$cultural_incubator){
            return response()->json(new JsonResponse([], 'Cultural incubator not exists'), Response::HTTP_BAD_REQUEST);
        }

        return response()->json(new JsonResponse($cultural_incubator, '', 'Cultural incubator details'), Response::HTTP_OK);
    }

    /**
     * Save cultural incubator request
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCulturalIncubatorRequest(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'project_name' => 'required|min:4',
            'email' => 'required|email',
            'country' => 'required',
            'phone' => 'required|min:8',
            'message' => 'required',
            'company' => 'required',
            'full_name' => 'required|min:4',
            'occupation' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json(new JsonResponse([], $validation->errors(), ''), Response::HTTP_OK);

        } else {
            $data = $request->all();

            DB::beginTransaction();
            $request = CulturalIncubatorRequest::make($data);
            $request->save();
            DB::commit();
            MailController::culturalIncubatorRequest($request);

            return response()->json(new JsonResponse([], '', 'Cultural incubator request sent successfully'), Response::HTTP_OK);
        }
    }


    /**
     * Save Cotact Form
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeContact(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'country' => 'required',
            'phone' => 'required|min:8',
            'message' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json(new JsonResponse([], $validation->errors(), ''), Response::HTTP_OK);

        } else {
            $data = $request->all();

            DB::beginTransaction();
            $contact = Contact::make($data);
            $contact->save();
            DB::commit();
            MailController::contactRequest($contact);

            return response()->json(new JsonResponse([], '', 'Contact request sent successfully'), Response::HTTP_OK);
        }
    }

}
