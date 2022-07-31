<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Notifications\ConfirmEmailNotification;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\RegisterNotification;
use App\Notifications\ApplyToOpportunityNotification;
use App\Notifications\ContactRequestNotification;
use App\Notifications\RecommendMemberNotification;
use App\Notifications\CulturalIncubatorRequestNotification;

use Notification;

class MailController extends Controller
{
    public static function register($user, $token)
    {
        $user->notify(new RegisterNotification($token));
    }
    
    public static function confirmEmail($user, $token)
    {
        $user->notify(new ConfirmEmailNotification($token));
    }

    public static function resetPassword($user, $token)
    {
        $user->notify(new ResetPasswordNotification($token));
    }

    public static function applyToOpportunity($user, $opportunity)
    {
        $user->notify(new ApplyToOpportunityNotification($opportunity));
    }

    public static function contactRequest($user)
    {
      $user->notify(new ContactRequestNotification());
    }

    public static function recommendMember($user, $userRecommendation)
    {
        $user->notify(new RecommendMemberNotification($userRecommendation));
    }

    public static function culturalIncubatorRequest($user)
    {
      $user->notify(new CulturalIncubatorRequestNotification());
    }
}
