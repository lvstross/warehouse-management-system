        <!-- Navigation -->
            <nav class="navbar navbar-inverse main-color navbar-fixed-top" role="navigation">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    @if( Auth::user()->permission != 4)
                        <a class="navbar-brand" href="{{ url('/dashboard') }}">Dataentry</a>
                    @endif
                </div>
                <!-- Top Menu Items -->
                <ul class="nav navbar-right top-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {{ Auth::user()->name }} <b class="caret"></b></a>
                        <ul class="dropdown-menu" style="width: 170px;">
                            @if( Auth::user()->permission == 1 )
                                <li class="padding">
                                    <a href="{{ url('users') }}"><i class="fa fa-fw fa-user" aria-hidden="true"></i> Users</a>
                                </li>
                                <li class="divider"></li>
                                <li class="padding">
                                    <a href="{{ url('/settings') }}"><i class="fa fa-fw fa-gear"></i> Settings</a>
                                </li>
                                <li class="divider"></li>
                                <li class="padding">
                                    <a href="{{ url('/systemlog') }}"><i class="fa fa-fw fa-book"></i> System Log</a>
                                </li>
                                <li class="divider"></li>
                            @endif
                            <li class="padding">
                                <a href="{{ route('logout') }}" 
                                    onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                    <i class="fa fa-fw fa-power-off"></i> 
                                    Log Out
                                </a>
                                
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
                @if( Auth::user()->permission != 4)
                    <!-- Toggle buttons -->
                    <div id="toggle-btn">
                        <button id="inner-toggle" type="button"><i class="fa fa-fw fa-bars fa-2x pull-right" aria-hidden="true"></i></button>
                    </div>
                    <div id="toggle-page">
                        <button id="inner-toggle-page" type="button"><i class="fa fa-fw fa-arrows-alt fa-2x pull-right" aria-hidden="true"></i></button>
                    </div>
                    <!-- End of toggle buttons -->
                    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
                    <div class="collapse navbar-collapse navbar-ex1-collapse">
                        <ul class="nav navbar-nav side-nav main-color">
                            <li>
                                <a href="{{ url('/dashboard') }}"><i class="fa fa-fw fa-dashboard" aria-hidden="true"></i> Dashboard</a>
                            </li>
                            @if( Auth::user()->permission == 1 || Auth::user()->permission == 2 )
                                <li>
                                    <a href="javascript:;" data-toggle="collapse" data-target="#pos"><i class="fa fa-fw fa-tasks" aria-hidden="true"></i> Purchase Orders <i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="pos" class="collapse">
                                            <li><a href="{{ url('purchaseorders?app=create') }} "><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Create Purchase Order</a></li>
                                            <li><a href="{{ url('purchaseorders?app=open') }} "><i class="fa fa-fw fa-folder-open" aria-hidden="true"></i> Current Open Order Report</a></li>
                                            <li><a href="{{ url('purchaseorders?app=closed') }} "><i class="fa fa-fw fa-folder" aria-hidden="true"></i> Closed Order</a></li>
                                    </ul>
                                </li>
                            @endif
                            @if( Auth::user()->permission == 1 || Auth::user()->permission == 2 || Auth::user()->permission == 3 )
                                <li>
                                    <a href="javascript:;" data-toggle="collapse" data-target="#routers"><i class="fa fa-fw fa-arrows" aria-hidden="true"></i> Routers <i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="routers" class="collapse">
                                            <li><a href="{{ url('production?app=addRouter') }} "><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Create Router</a></li>
                                            <li><a href="{{ url('production?app=viewRouters') }} "><i class="fa fa-fw fa-arrows" aria-hidden="true"></i> View Routers</a></li>
                                            <li><a href="{{ url('production?app=partflow') }} "><i class="fa fa-fw fa-columns" aria-hidden="true"></i> Partflow</a></li>
                                    </ul>
                                </li>
                            @endif
                            @if( Auth::user()->permission == 1 || Auth::user()->permission == 2 )
                                <li>
                                    <a href="javascript:;" data-toggle="collapse" data-target="#invoice"><i class="fa fa-fw fa-money" aria-hidden="true"></i> Invoices <i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="invoice" class="collapse">
                                            <li><a href="{{ url('invoices?app=addInvoice') }} "><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Create Invoice</a></li>
                                            <li><a href="{{ url('invoices?app=viewInvoices') }} "><i class="fa fa-fw fa-money" aria-hidden="true"></i> View Invoices</a></li>
                                    </ul>
                                </li>
                            @endif
                            @if( Auth::user()->permission == 1 || Auth::user()->permission == 2 || Auth::user()->permission == 3 )
                                <li>
                                    <a href="javascript:;" data-toggle="collapse" data-target="#inventory"><i class="fa fa-fw fa-ticket" aria-hidden="true"></i> Inventory &amp; Ship Tickets <i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="inventory" class="collapse">
                                        <li><a href="{{ url('production?app=searchInventory') }} "><i class="fa fa-search-plus" aria-hidden="true"></i> Search Inventory</a></li>
                                        <li><a href="{{ url('production?app=addInventory') }} "><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Create Inventory Ship Ticket</a></li>
                                        <li><a href="{{ url('production?app=viewInventory') }} "><i class="fa fa-fw fa-ticket" aria-hidden="true"></i> View Inventory Ship Tickets</a></li>
                                    </ul>
                                </li>
                            @endif
                            @if( Auth::user()->permission == 1 || Auth::user()->permission == 2 || Auth::user()->permission == 3 )
                                <li><a href="{{ url('certifications') }} "><i class="fa fa-fw fa-certificate"></i> Certifications</a></li>
                            @endif
                            @if( Auth::user()->permission == 1 || Auth::user()->permission == 2 )
                                <li>
                                    <a href="javascript:;" data-toggle="collapse" data-target="#purch"><i class="fa fa-fw fa-credit-card" aria-hidden="true"></i> Purchases &amp; Receivals <i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="purch" class="collapse">
                                            <li><a href="{{ url('purchases?app=add') }} "><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Add Purchase</a></li>
                                            <li><a href="{{ url('purchases?app=purchases') }} "><i class="fa fa-fw fa-credit-card" aria-hidden="true"></i> View Purchases</a></li>
                                            <li><a href="{{ url('purchases?app=receivals') }} "><i class="fa fa-fw fa-paperclip" aria-hidden="true"></i> View Receivals</a></li>
                                    </ul>
                                </li>
                            @endif
                            <li>
                                <a href="javascript:;" data-toggle="collapse" data-target="#sysd"><i class="fa fa-fw fa-database" aria-hidden="true"></i> System Data <i class="fa fa-fw fa-caret-down"></i></a>
                                <ul id="sysd" class="collapse">
                                    @if( Auth::user()->permission == 1 || Auth::user()->permission == 2 )
                                        <li><a href="{{ url('customers') }}"><i class="fa fa-fw fa-users" aria-hidden="true"></i> Customers</a></li>
                                    @endif
                                    @if( Auth::user()->permission == 1 || Auth::user()->permission == 2 )
                                        <li><a href="{{ url('production?app=departments') }}"><i class="fa fa-fw fa-wrench" aria-hidden="true"></i> Departments</a></li>
                                    @endif
                                    @if( Auth::user()->permission == 1 || Auth::user()->permission == 2 || Auth::user()->permission == 3 )
                                        <li><a href="{{ url('products') }}"><i class="fa fa-fw fa-plane" aria-hidden="true"></i> Products</a></li>
                                    @endif
                                    @if( Auth::user()->permission == 1 || Auth::user()->permission == 2 || Auth::user()->permission == 3 )
                                        <li><a href="{{ url('vendors') }}"><i class="fa fa-fw fa-truck" aria-hidden="true"></i> Vendors</a></li>
                                    @endif
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
                @endif
            </nav>

        <div id="page-wrapper">

            <div class="container-fluid">