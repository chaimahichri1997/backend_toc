<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;

class ContactController extends Controller
{

    const ITEM_PER_PAGE = 15;



    /**
     * Get List of contacts with pagination and search params
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllContact(Request $request)
    {
        $searchParams = $request->all();
        $contacts = Contact::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $keyword = Arr::get($searchParams, 'keyword', '');


        if (!empty($keyword)) {
            $contacts->where('name', 'LIKE', '%' . $keyword . '%');
            $contacts->orWhere('email', 'LIKE', '%' . $keyword . '%');
            $contacts->orWhere('message', 'LIKE', '%' . $keyword . '%');
        }

        return response()->json(new JsonResponse($contacts->paginate($limit), '', ''), Response::HTTP_OK);
    }

    /**
     * Get Contact By Id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContactById(Request $request)
    {
        $contact = Contact::where('id', $request->contactId)->first();
        return response()->json(new JsonResponse($contact, '', ''), Response::HTTP_OK);
    }

    /**
     * Delete Contact
     * @param Request $request
     * @param $contactId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteContact(Request $request, $contactId)
    {
        $currentUser = $request->user();

        if ($currentUser->hasPermission('delete_contact'))
        {
            try {
                $contact = Contact::findorfail($contactId)->delete();

                return response()->json(new JsonResponse([], '', 'Contact deleted successfully'), Response::HTTP_OK);

            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(new JsonResponse([], '404 not found'), Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json(new JsonResponse([], '', 'Permission denied'), Response::HTTP_FORBIDDEN);
        }
    }
}
