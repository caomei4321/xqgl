<?php

use Illuminate\Database\Seeder;

class AdministratorPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrator = \App\Models\Admin::find(1);
        $permission = \Spatie\Permission\Models\Permission::all();

        $administrator->syncPermissions($permission);
    }
}
