@if (session()->has('success'))
    <div class="col-md-12">
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                <i class="nc-icon nc-simple-remove"></i>
            </button>
            <span><b> Sucesso:</b> {{ session('success') }}</span>
        </div>
    </div>

@endif


@if (session()->has('error'))
    <div class="col-md-12">
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                <i class="nc-icon nc-simple-remove"></i>
            </button>
            <span><b> Atenção !:</b> {{ session('error') }}</span>
        </div>
    </div>
@endif
