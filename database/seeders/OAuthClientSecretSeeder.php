<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OAuthClientSecretSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("oauth_clients")
            ->insert(
                [
                    [
                        "id" => 1,
                        "name" => "TOC Client",
                        "secret" => "jR4BHbvpcLoEE2NsQH5zbE2EW3sh1Qh6WV3NrAN2",
                        "redirect" => " ",
                        "password_client" => TRUE,
                        "personal_access_client" => TRUE,
                        "created_at" => now(),
                        "updated_at" => now(),
                        "revoked" => FALSE,

                    ],
                ]
            );
        DB::table("oauth_personal_access_clients")
            ->insert(
                [
                    [
                        'client_id' => 1,
                        "created_at" => now(),
                        "updated_at" => now(),
                    ],
                ]
            );
    }
}
