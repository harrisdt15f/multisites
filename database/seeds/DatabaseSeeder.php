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
        $this->call(AccountChangeReportsTableSeeder::class);
        $this->call(AccountChangeTypesTableSeeder::class);
        $this->call(ActivityBetLogsTableSeeder::class);
        $this->call(BackendAdminAccessGroupsTableSeeder::class);
        $this->call(BackendAdminAuditFlowListsTableSeeder::class);
        $this->call(BackendAdminAuditPasswordsListsTableSeeder::class);
        $this->call(BackendAdminMessageArticlesTableSeeder::class);
        $this->call(BackendAdminRechargePermitGroupsTableSeeder::class);
        $this->call(BackendAdminRechargePocessAmountsTableSeeder::class);
        $this->call(BackendAdminRechargehumanLogsTableSeeder::class);
        $this->call(BackendAdminRoutesTableSeeder::class);
        $this->call(BackendAdminUsersTableSeeder::class);
        $this->call(BackendSystemInternalMessagesTableSeeder::class);
        $this->call(BackendSystemLogsTableSeeder::class);
        $this->call(BackendSystemMenusTableSeeder::class);
        $this->call(BackendSystemNoticeListsTableSeeder::class);
        $this->call(FrontendActivityContentsTableSeeder::class);
        $this->call(FrontendAllocatedModelsTableSeeder::class);
        $this->call(FrontendAppRoutesTableSeeder::class);
        $this->call(FrontendInfoCategoriesTableSeeder::class);
        $this->call(FrontendLinksRegisteredUsersTableSeeder::class);
        $this->call(FrontendLotteryFnfBetableListsTableSeeder::class);
        $this->call(FrontendLotteryFnfBetableMethodsTableSeeder::class);
        $this->call(FrontendLotteryRedirectBetListsTableSeeder::class);
        $this->call(FrontendMessageNoticesTableSeeder::class);
        $this->call(FrontendPageBannersTableSeeder::class);
        $this->call(FrontendSystemAdsTypesTableSeeder::class);
        $this->call(FrontendSystemBanksTableSeeder::class);
        $this->call(FrontendSystemLogsTableSeeder::class);
        $this->call(FrontendUserDividendConfigsTableSeeder::class);
        $this->call(FrontendUserDividendReportsTableSeeder::class);
        $this->call(FrontendUserInvitedRecordsTableSeeder::class);
        $this->call(FrontendUsersTableSeeder::class);
        $this->call(FrontendUsersAccountsTableSeeder::class);
        $this->call(FrontendUsersBankCardsTableSeeder::class);
        $this->call(FrontendUsersPrivacyFlowsTableSeeder::class);
        $this->call(FrontendUsersRegisterableLinksTableSeeder::class);
        $this->call(FrontendUsersTransferedRecordsTableSeeder::class);
        $this->call(FrontendWebRoutesTableSeeder::class);
        $this->call(LotteryIssueRulesTableSeeder::class);
        $this->call(LotteryIssuesTableSeeder::class);
        $this->call(LotteryListsTableSeeder::class);
        $this->call(LotteryMethodsTableSeeder::class);
        $this->call(LotteryMethodsWaysLevelsTableSeeder::class);
        $this->call(LotterySeriesTableSeeder::class);
        $this->call(LotteryTraceListsTableSeeder::class);
        $this->call(LotteryTracesTableSeeder::class);
        $this->call(ProjectsTableSeeder::class);
        $this->call(SystemConfigurationsTableSeeder::class);
        $this->call(SystemPlatformsTableSeeder::class);
        $this->call(UserTransferRecordsTableSeeder::class);
        $this->call(UsersRechargeHistoriesTableSeeder::class);
        $this->call(UsersRechargeLogsTableSeeder::class);
        $this->call(UsersRegionsTableSeeder::class);
        $this->call(UsersSalaryConfigsTableSeeder::class);
        $this->call(UsersSalaryReportsTableSeeder::class);
        $this->call(UsersStatDaysTableSeeder::class);
        $this->call(UsersWithdrawAuditListsTableSeeder::class);
        $this->call(UsersWithdrawHistoriesTableSeeder::class);
        $this->call(UsersWithdrawLogsTableSeeder::class);
    }
}
