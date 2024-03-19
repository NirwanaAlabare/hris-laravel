@if(Session::get('success'))
    <div class="alert alert-success text-center"><i class="fa fa-check"></i> {!! Session::get('success') !!}</div>
@endif
