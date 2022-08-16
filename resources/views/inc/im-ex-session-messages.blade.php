@if (session('customer-import-status'))
    <div class="full-width alert alert-success alert-dismissable text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('customer-import-status') }}
    </div>
@elseif(session('customer-import-status-error'))
    <div class="full-width alert alert-danger alert-dismissable text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('customer-import-status-error') }}
    </div>
@endif

@if (session('department-import-status'))
    <div class="full-width alert alert-success alert-dismissable text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('department-import-status') }}
    </div>
@elseif(session('department-import-status-error'))
    <div class="full-width alert alert-danger alert-dismissable text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('department-import-status-error') }}
    </div>
@endif

@if (session('inventory-import-status'))
    <div class="full-width alert alert-success alert-dismissable text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('inventory-import-status') }}
    </div>
@elseif(session('inventory-import-status-error'))
    <div class="full-width alert alert-danger alert-dismissable text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('inventory-import-status-error') }}
    </div>
@endif

@if (session('invoice-import-status'))
    <div class="full-width alert alert-success alert-dismissable text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('invoice-import-status') }}
    </div>
@elseif(session('invoice-import-status-error'))
    <div class="full-width alert alert-danger alert-dismissable text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('invoice-import-status-error') }}
    </div>
@endif

@if (session('product-import-status'))
    <div class="full-width alert alert-success alert-dismissable text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('product-import-status') }}
    </div>
@elseif(session('product-import-status-error'))
    <div class="full-width alert alert-danger alert-dismissable text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('product-import-status-error') }}
    </div>
@endif

@if (session('purchaseorders-import-status'))
    <div class="full-width alert alert-success alert-dismissable text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('purchaseorders-import-status') }}
    </div>
@elseif(session('purchaseorders-import-status-error'))
    <div class="full-width alert alert-danger alert-dismissable text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('purchaseorders-import-status-error') }}
    </div>
@endif

@if (session('purchases-import-status'))
    <div class="full-width alert alert-success alert-dismissable text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('purchases-import-status') }}
    </div>
@elseif(session('purchases-import-status-error'))
    <div class="full-width alert alert-danger alert-dismissable text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('purchases-import-status-error') }}
    </div>
@endif

@if (session('router-import-status'))
    <div class="full-width alert alert-success alert-dismissable text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('router-import-status') }}
    </div>
@elseif(session('router-import-status-error'))
    <div class="full-width alert alert-danger alert-dismissable text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('router-import-status-error') }}
    </div>
@endif

@if (session('systemlog-import-status'))
    <div class="full-width alert alert-success alert-dismissable text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('systemlog-import-status') }}
    </div>
@elseif(session('systemlog-import-status-error'))
    <div class="full-width alert alert-danger alert-dismissable text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('systemlog-import-status-error') }}
    </div>
@endif

@if (session('vendor-import-status'))
    <div class="full-width alert alert-success alert-dismissable text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('vendor-import-status') }}
    </div>
@elseif(session('vendor-import-status-error'))
    <div class="full-width alert alert-danger alert-dismissable text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('vendor-import-status-error') }}
    </div>
@endif

@if (session('company-import-status'))
    <div class="full-width alert alert-success alert-dismissable text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('company-import-status') }}
    </div>
@elseif(session('company-import-status-error'))
    <div class="full-width alert alert-danger alert-dismissable text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('company-import-status-error') }}
    </div>
@endif