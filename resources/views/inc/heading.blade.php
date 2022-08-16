            @if(Request::is('purchaseorders'))
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header text-center">
                            Purchase Orders
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
            @elseif(Request::is('dashboard'))
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header text-center">
                            Dashboard
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
            @elseif(Request::is('users'))
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header text-center">
                            Users
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
            @elseif(Request::is('products'))
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header text-center">
                            Products
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
            @elseif(Request::is('certifications'))
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header text-center">
                            Certifications
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
            @elseif(Request::is('invoices'))
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header text-center">
                            Invoices
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
            @elseif(Request::is('customers'))
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header text-center">
                            Customers
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
            @elseif(Request::is('settings'))
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header text-center">
                            Settings
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
            @elseif(Request::is('inventory'))
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header text-center">
                            Inventory
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
            @elseif(Request::is('systemlog'))
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header text-center">
                            System Log
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
            @elseif(Request::is('vendors'))
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header text-center">
                            Vendors
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
            @elseif(Request::is('purchases'))
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header text-center">
                            Purchases
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
            @else
                {{-- Nothing --}}
            @endif