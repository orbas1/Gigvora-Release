@extends('install.index')

@section('content')
@if(isset($error) && $error != "")
  <div class="row ins-seven">
    <div class="col-md-8 col-md-offset-2">
      <div class="alert alert-danger">
        <strong>{{ $error }}</strong>
      </div>
    </div>
  </div>
@endif
<div class="row justify-content-center ins-two">
  <div class="col-md-6">
    <div class="card">
      <div class="card-body px-4">
        <div class="panel panel-default ins-three" data-collapsed="0">
          <div class="panel-body ins-four">
            <p class="ins-four">
              {{ __('Purchase code validation has been removed for Gigvora installs. Continue to database setup when ready.') }}
            </p>
            <br>
            <div class="d-flex justify-content-end">
              <a href="{{ route('step3') }}" class="btn btn-primary">
                {{ __('Continue to Database Setup') }}
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
