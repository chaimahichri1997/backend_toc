<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
                // Reset cached roles and permissions
                app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

                // permissions
                Permission::updateOrCreate(['name' => 'create_user']);
                Permission::updateOrCreate(['name' => 'edit_user']);
                Permission::updateOrCreate(['name' => 'delete_user']);

                Permission::updateOrCreate(['name' => 'list_roles']);
                Permission::updateOrCreate(['name' => 'create_role']);
                Permission::updateOrCreate(['name' => 'update_role']);
                Permission::updateOrCreate(['name' => 'delete_role']);

                Permission::updateOrCreate(['name' => 'list_permissions']);
                Permission::updateOrCreate(['name' => 'create_permission']);
                Permission::updateOrCreate(['name' => 'update_permission']);
                Permission::updateOrCreate(['name' => 'delete_permission']);

                //Contact
                Permission::updateOrCreate(['name' => 'list_contacts']);
                Permission::updateOrCreate(['name' => 'delete_contact']);
                Permission::updateOrCreate(['name' => 'edit_contact']);

                // Collections
                Permission::updateOrCreate(['name' => 'list_collections']);
                Permission::updateOrCreate(['name' => 'create_collection']);
                Permission::updateOrCreate(['name' => 'update_collection']);
                Permission::updateOrCreate(['name' => 'delete_collection']);
                Permission::updateOrCreate(['name' => 'get_collection_logs']);
                // Artists
                Permission::updateOrCreate(['name' => 'list_artists']);
                Permission::updateOrCreate(['name' => 'create_artist']);
                Permission::updateOrCreate(['name' => 'update_artist']);
                Permission::updateOrCreate(['name' => 'delete_artist']);
                Permission::updateOrCreate(['name' => 'get_artist_logs']);
                // Art Munchies
                Permission::updateOrCreate(['name' => 'list_artMuncies']);
                Permission::updateOrCreate(['name' => 'create_artMunchie']);
                Permission::updateOrCreate(['name' => 'update_artMunchie']);
                Permission::updateOrCreate(['name' => 'delete_artMunchie']);
                Permission::updateOrCreate(['name' => 'get_artMunchie_logs']);

                // Pages
                Permission::updateOrCreate(['name' => 'list_pages']);
                Permission::updateOrCreate(['name' => 'create_page']);
                Permission::updateOrCreate(['name' => 'update_page']);
                Permission::updateOrCreate(['name' => 'delete_page']);
                Permission::updateOrCreate(['name' => 'get_basic_pages_logs']);

                // Cultural Incubators
                Permission::updateOrCreate(['name' => 'list_cultural_incubators']);
                Permission::updateOrCreate(['name' => 'create_cultural_incubator']);
                Permission::updateOrCreate(['name' => 'update_cultural_incubator']);
                Permission::updateOrCreate(['name' => 'delete_cultural_incubator']);
                Permission::updateOrCreate(['name' => 'get_cultural_incubator_logs']);


                // Cultural Incubators Requests
                Permission::updateOrCreate(['name' => 'list_cultural_incubators_requests']);
                Permission::updateOrCreate(['name' => 'delete_cultural_incubator_request']);

                // art works
                Permission::updateOrCreate(['name' => 'list_art_works']);
                Permission::updateOrCreate(['name' => 'create_art_work']);
                Permission::updateOrCreate(['name' => 'update_art_work']);
                Permission::updateOrCreate(['name' => 'delete_art_work']);
                Permission::updateOrCreate(['name' => 'restore_art_work']);
                Permission::updateOrCreate(['name' => 'get_artwork_logs']);
                Permission::updateOrCreate(['name' => 'add_artwork_to_explore']);
                Permission::updateOrCreate(['name' => 'add_artwork_to_basket']);





                // create roles and assign created permissions

                $role = Role::updateOrCreate(['name' => 'super-admin']);
                $role->givePermissionTo(Permission::all());

                $role = Role::updateOrCreate(['name' => 'member']);
                //        $role->givePermissionTo([
                //            'update_profile',
                //            'list_agents',
                //            'create_agent',
                //            'update_agent',
                //            'delete_agent',
                //        ]);

                $role = Role::updateOrCreate(['name' => 'artist']);
                //        $role->givePermissionTo([
                //            'update_profile',
                //        ]);

                $role = Role::updateOrCreate(['name' => 'collectioneur']);
                //        $role->givePermissionTo([
                //            'update_profile',
                //        ]);

        }
}
