<?php

// This class file to define all general functions

namespace App\Helpers;


use App\Models\InstagramData;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InstagramScraper\Instagram;
use Phpfastcache\Helper\Psr16Adapter;
use InstagramScraper\Client;

class Helper
{
    static function URLSlug($title)
    {
        $slug = Str::slug($title, '-');
        return $slug;
    }

    static function fetchInstagramData()
    {
//        $instagram = Instagram::withCredentials(new \GuzzleHttp\Client(), env('INSTAGRAM_USERNAME'), env('INSTAGRAM_PASSWORD'), new Psr16Adapter('Files'));
//        $instagram->login();
//        $instagram->saveSession();
//
//        $posts  = $instagram->getFeed();
//
//        $user_id = $instagram->getCurrentUserInfo()->getId();
//
//        $res = $instagram->getAccountById($user_id);
//
//        $medias = $res->getMedias();


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://instagram130.p.rapidapi.com/account-feed?username=medbadis7',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'X-RapidAPI-Host: instagram130.p.rapidapi.com',
                'X-RapidAPI-Key: 5f00cdcb72msh80951d5d120667fp13fccdjsn7bb46a6e037e'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $data = json_decode($response) ;

        foreach ($data as $media)
        {
            $folderName = 'instagram';

            $folderPath = Storage::disk('public')->makeDirectory($folderName, 0777);

            $contents = file_get_contents($media->node->display_url);
            $extension = pathinfo(parse_url($media->node->display_url, PHP_URL_PATH), PATHINFO_EXTENSION);

            $fileName = $media->node->id . '.' . $extension;


            Storage::disk('public')->put($folderName . '/' . $fileName, $contents);

            $image_path = env('APP_URL').'/storage/'.$folderName.'/'.$fileName;

            $response = InstagramData::updateOrCreate([
                "code" => $media->node->id
            ], [
                "comments" => $media->node->edge_media_to_comment->count,
                "likes" => $media->node->edge_media_preview_like->count,
                "path" => env('APP_URL').'/MediaProxy?url='.json_encode($media->node->display_url),
                "image"=>$image_path,
            ]);
        }
        return 'ok';
    }
}
