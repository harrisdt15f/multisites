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
        $this->call(AccountChangeTypePastTableSeeder::class);
        $this->call(AdminGroupsTableSeeder::class);
        $this->call(AdminMenusTableSeeder::class);
        $this->call(AdminUsersTableSeeder::class);
        $this->call(BanksTableSeeder::class);
        $this->call(IssueRulesTableSeeder::class);
        $this->call(LotteriesTableSeeder::class);
        $this->call(MethodsTableSeeder::class);
        $this->call(PartnerActivityListsTableSeeder::class);
        $this->call(PartnerAdminGroupsTableSeeder::class);
        $this->call(PartnerCategoryTableSeeder::class);
        $this->call(PartnerPlatformsTableSeeder::class);
        $this->call(RegionTableSeeder::class);
        $this->call(SysConfiguresTableSeeder::class);
    }
}
