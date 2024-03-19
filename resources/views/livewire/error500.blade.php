@extends('layouts.customapp')

@section('custom-styles')

@endsection

@section('content')

			<!-- page-content -->
			<div class="page-content">
				<div class="container text-center text-dark">
					<div class="display-1  text-dark mb-5">500</div>
					<p class="h5 font-weight-normal mb-7 leading-normal">Internal Server Error...</p>
					<a class="btn btn-primary  mb-5" href="{{url('index')}}">
						Back To Home
					</a>
				</div>
			</div>
			<!-- page-content end -->

@endsection('content')

@section('custom-scripts')

@endsection