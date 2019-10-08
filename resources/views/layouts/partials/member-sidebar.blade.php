<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li class="{{ request()->is('member/dashboard') ? 'active' : '' }}">
                <a href="{{ route('member.dashboard') }}">
                    <i class='fa fa-tachometer'></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="treeview {{ request()->is('member/downlines/*') ? 'active' : '' }}">
                <a href="#"><i class='fa fa-sitemap'></i> <span>Downlines</span> <i
                        class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li class="{{ request()->is('member/downlines/hierarchy') ? 'active' : '' }}">
                        <a href="{{ route('member.downlines.hierarchy') }}">
                            <span>Hierarchy Chart</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('member/downlines/tabular') ? 'active' : '' }}">
                        <a href="{{ route('member.downlines.tabular') }}">
                            <span>Tabular Chart</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('member/downlines/create') ? 'active' : '' }}">
                        <a href="{{ route('member.downlines.create') }}">
                            <span>New Member</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('member/downlines/list') ? 'active' : '' }}">
                        <a href="{{ route('member.downlines.list') }}">
                            <span>List</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ request()->is('member/wallets/*') ? 'active' : '' }}">
                <a href="#"><i class='fa fa-briefcase'></i> <span>Wallets</span> <i
                        class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li class="{{ request()->is('member/wallets/transactions') ? 'active' : '' }}">
                        <a href="{{ route('member.wallets.transactions') }}">
                            <span>Transactions</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('member/wallets/transfers') ? 'active' : '' }}">
                        <a href="{{ route('member.wallets.transfers') }}">
                            <span>Transfers</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('member/wallets/withdrawals') ? 'active' : '' }}">
                        <a href="{{ route('member.wallets.withdrawals') }}">
                            <span>Withdrawals</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="{{ request()->is('member/payouts*') ? 'active' : '' }}">
                <a href="{{ route('member.payouts.index') }}">
                    <i class='fa fa-money'></i> <span>Payouts</span>
                </a>
            </li>
            <li class="{{ request()->is('member/mailbox*') ? 'active' : '' }}">
                <a href="{{ route('member.mailbox.inbox') }}"><i class='fa fa-envelope-o'></i> <span>Mailbox</span></a>
            </li>
            <li class="{{ request()->is('member/upgrade*') ? 'active' : '' }}">
                <a href="{{ route('member.upgrade') }}"><i class='fa fa-level-up'></i> <span>Upgrade Plan</span></a>
            </li>
        </ul>
    </section>
</aside>