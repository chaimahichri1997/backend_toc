<?php

namespace App\Http\Controllers\Api\backend;

use App\Http\Controllers\Controller;
use App\Models\ArtWork;
use App\Models\User;
use App\Models\UserBasket;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use DB;

class ProfileController extends Controller
{


    public function getFavoriteArtwork(Request $request)
    {
        $currentUser = $request->user();
        $artwork_user = $currentUser->favorit_artwork;
        if ($artwork_user) {
            return response()->json(new JsonResponse($artwork_user, '', ''), Response::HTTP_OK);
        }
        return response()->json(new JsonResponse([], '', 'No favorite found'), Response::HTTP_OK);
    }


    public function getBasketContent(Request $request)
    {
        $currentUser = $request->user();
        $artwork_basket = $currentUser->artwork_in_basket;
        if ($artwork_basket) {
            return response()->json(new JsonResponse($artwork_basket, '', ''), Response::HTTP_OK);
        }
        return response()->json(new JsonResponse([], '', 'No artwork found'), Response::HTTP_OK);
    }

    public function AddArtworkToBasket(Request $request, $artworkId)
    {
        $currentUser = $request->user();
        if (
            $currentUser->hasPermission('update_collection')
        ) {

            $artwork = ArtWork::findOrFail($artworkId);
            //   if (UserBasket::where(['art_work_id', $artwork], ['user_id', $currentUser])) {
            //       return response()->json(new JsonResponse(['ok'], '', 'artwork in basket'), Response::HTTP_OK);
            //   }

            DB::beginTransaction();

            $artwork->artwork_in_basket()->attach($currentUser->id);

            DB::commit();

            return response()->json(new JsonResponse([], '', 'artwork in basket'), Response::HTTP_OK);
        }
        return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
    }

    public function syncUserArtworkFavoris(Request $request, $artwork_id)
    {
        $currentUser = $request->user();
        if (
            $currentUser->hasPermission('update_collection')
        ) {

            $artwork = ArtWork::findOrFail($artwork_id);


            DB::beginTransaction();

            $artwork->user_favorit()->attach($currentUser->id);

            DB::commit();

            return response()->json(new JsonResponse([], '', 'artwork in favoris'), Response::HTTP_OK);
        }
        return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
    }



    public function deletefrombasket(Request $request, $basket_id)
    {
        $currentUser = $request->user();

        if ($currentUser->hasPermission('delete_collection')) {
            try {
                $userbasket = UserBasket::find($basket_id)->delete();

                return response()->json(new JsonResponse([$userbasket], '', 'artwork deleted successfully'), Response::HTTP_OK);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(new JsonResponse([$userbasket], '404 not found'), Response::HTTP_BAD_REQUEST);
            }
        } else {
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








    public function updateProfile(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'first_name' => 'required|min:4',
            'last_name' => 'required|min:4',
            'email' => 'required|email',
            'country' => 'required',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8',
        ]);

        if ($validation->fails()) {
            return response()->json(new JsonResponse([], $validation->errors(), ''), Response::HTTP_OK);
        } else {
            $user = User::findOrFail($request->user()->id);
            $data = $request->all();
            DB::beginTransaction();

            $user->update($data);

            DB::commit();
            return response()->json(new JsonResponse([], '', 'Profile updated successfully'), Response::HTTP_OK);
        }
        return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
    }

    public function showProfile(Request $request)
    {
        $user = User::findOrFail($request->user()->id);

        return response()->json(new JsonResponse($user, '',), Response::HTTP_OK);
    }
}
