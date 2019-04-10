<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(PartnerAccessGroupTableSeeder::class);
        $this->call(PartnerAdminMenusTableSeeder::class);
        $this->call(PartnerAdminRouteTableSeeder::class);
        $this->call(PartnerAdminUsersTableSeeder::class);
        $this->call(PartnerUsersTableSeeder::class);
        $this->call(PlatformsTableSeeder::class);
        $this->call(PartnerSysConfiguresTableSeeder::class);
        $this->call(RegisterLinkUsersTableSeeder::class);
        $this->call(RegisterLinksTableSeeder::class);
    }
}
