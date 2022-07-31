<?php

namespace App\Jobs;

use App\Models\InstagramData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class fetchInstagramData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
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
                "path" => $media->node->display_url,
                "image"=>$image_path,
            ]);
        }
        return 'ok';

    }
}
