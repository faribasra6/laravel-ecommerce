@if(session('success'))
    <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Success!</h4>
        <p>{!! session('success') !!}</p>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">Error!</h4>
        <p>{!!session('error') !!}</p>
    </div>
@endif
