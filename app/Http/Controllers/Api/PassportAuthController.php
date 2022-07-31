<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;

class PassportAuthController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Registration Request
     */
    public function register(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'first_name' => 'required|min:4',
            'last_name' => 'required|min:4',
            'email' => 'required|email|unique:users',
            'country' => 'required',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8',
        ]);

        if ($validation->fails()) {
            return response()->json(new JsonResponse([], $validation->errors(), ''), Response::HTTP_OK);
        } else {
            $customerId = $this->createCustomerId();
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'country' => $request->country,
                'password' => bcrypt($request->password),
                'role' => $request->role,
                'customer_id' => $customerId,

            ]);
            $user->assignRole('super-admin');
            $user->save();

            if (!$user) {
                return response()->json(new JsonResponse([], '', 'Error while registration!'), Response::HTTP_OK);
            }
            $tokenResult = $user->createToken('confirm-email');
            $token = $tokenResult->accessToken;

            MailController::register($user, $token);


            return response()->json(new JsonResponse([], '', trans('custom.registred')), Response::HTTP_OK);
        }
    }


    public function createCustomerId()
    {
        $stripe = new \Stripe\StripeClient(
            'sk_test_51LPvKKGWFeTB9Tq2Rw8Ufli5GJVAm9tZQuE2co9DbVYFKGglXKX3O2X42nCetbqi3js2JIsA1e7DRmL3Uz8sqdh200rxRhx8YL'
        );
        $customer = $stripe->customers->create([
            'description' => 'My First Test Customer (created for API docs at https://www.stripe.com/docs/api)',
        ]);
        return $customer ? $customer['id'] : '';
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Login Request
     */
    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        if ($validation->fails()) {
            return response()->json(new JsonResponse([], $validation->errors(), ''), Response::HTTP_OK);
        } else {

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return response()->json(new JsonResponse([], trans('auth.failed'), ''), Response::HTTP_OK);
            }
            $user = $request->user();
            if (!$user->hasVerifiedEmail()) {
                $tokenResult = $user->createToken('confirm-email');
                $token = $tokenResult->accessToken;

                return response()->json(new JsonResponse([
                    'token' => $token,
                    'notVerified' => true
                ], trans('custom.verify_email'), ''), Response::HTTP_OK);
            }

            $tokenResult = $user->createToken('login');

            $token = $tokenResult->token;
            if ($request->remember_me)
                $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();

            return response()->json(new JsonResponse([
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'user' => $user,
                'roles' => $user->roles()->pluck('name'),
                //              'collections'=>$user->collections,
                'permissions' => $user->getPermissionsViaRoles()->pluck('name'),
                'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
            ], '', ''), Response::HTTP_OK);
        }
    }

    /**
     * Logout Request
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(new JsonResponse([], '', trans('custom.loged_out')), Response::HTTP_OK);
    }

    /**
     * Confirm Email Request
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthenticationException
     */
    public function confirmEmail(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            throw  new AuthenticationException("Unauthorized access");
        }

        if ($user->email_verified_at) {
            return response()->json(new JsonResponse([], '', trans('custom.verified')), Response::HTTP_OK);
        }

        $user->email_verified_at = now();
        $user->save();
        $tokens = $user->tokens;
        foreach ($tokens as $token) {
            $token->revoke();
        }
        return response()->json(new JsonResponse([], '', trans('custom.confirmed')), Response::HTTP_OK);
    }
}
