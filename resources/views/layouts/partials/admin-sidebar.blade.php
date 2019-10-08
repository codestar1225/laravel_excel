<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class='fa fa-tachometer'></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="{{ request()->is('admin/plans*') ? 'active' : '' }}">
                <a href="{{ route('admin.plans.index') }}">
                    <i class='fa fa-cubes'></i> <span>Investment Plans</span>
                </a>
            </li>
            <li class="{{ request()->is('admin/payouts*') ? 'active' : '' }}">
                <a href="{{ route('admin.payouts.index') }}">
                    <i class='fa fa-money'></i> <span>Payouts</span>
                </a>
            </li>
            <li class="treeview {{ request()->is('admin/members*') ? 'active' : '' }}">
                <a href="#"><i class='fa fa-sitemap'></i> <span>Members</span> <i
                        class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li class="{{ request()->is('admin/members/hierarchy') ? 'active' : '' }}">
                        <a href="{{ route('admin.members.hierarchy') }}">
                            <span>Hierarchy Chart</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/members/tabular') ? 'active' : '' }}">
                            <a href="{{ route('admin.members.tabular') }}">
                                <span>Tabular Chart</span>
                            </a>
                        </li>
                    <li class="{{ request()->is('admin/members') ? 'active' : '' }}">
                        <a href="{{ route('admin.members.list') }}">
                            <span>List</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="header">Approvals</li>
            <li class="{{ request()->is('admin/approvals/members*') ? 'active' : '' }}">
                <a href="{{ route('admin.approvals.members') }}">
                    <i class='fa fa-user-plus'></i> <span>New Member Approvals</span>
                </a>
            </li>
            <li class="{{ request()->is('admin/approvals/withdrawals*') ? 'active' : '' }}">
                <a href="{{ route('admin.approvals.withdrawals') }}">
                    <i class='fa fa-share-square-o'></i> <span>Withdrawal Approvals</span>
                </a>
            </li>
            <li class="{{ request()->is('admin/approvals/closings*') ? 'active' : '' }}">
                <a href="{{ route('admin.approvals.closings') }}">
                    <i class='fa fa-user-secret'></i> <span>Closing Approvals</span>
                </a>
            </li>
            <li class="{{ request()->is('admin/approvals/kyc*') ? 'active' : '' }}">
                <a href="{{ route('admin.approvals.kyc') }}">
                    <i class='fa fa-legal'></i> <span>KYC Approvals</span>
                </a>
            </li>
            <li class="{{ request()->is('admin/approvals/plans*') ? 'active' : '' }}">
                <a href="{{ route('admin.approvals.plans') }}">
                    <i class='fa fa-level-up'></i> <span>Plan Purchase Approvals</span>
                </a>
            </li>
            <li class="{{ request()->is('admin/mailbox*') ? 'active' : '' }}">
                <a href="{{ route('admin.mailbox.inbox') }}"><i class='fa fa-envelope-o'></i> <span>Mailbox</span></a>
            </li>
            <li class="{{ request()->is('admin/settings') ? 'active' : '' }}">
                <a href="{{ route('admin.settings') }}">
                    <i class='fa fa-cog'></i> <span>Settings</span>
                </a>
            </li>
        </ul>
    </section>
</aside>