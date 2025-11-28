@extends('install.index')
   
@section('content')
<?php if(isset($error) && $error != "") { ?>
  <div class="row ins-seven">
    <div class="col-md-8 col-md-offset-2">
      <div class="alert alert-danger">
        <strong>{{ $error }}</strong>
      </div>
    </div>
  </div>
<?php } ?>
<div class="row justify-content-center ins-two">
  <div class="col-md-6">
    <div class="card">
      <div class="card-body px-4">
        <div class="panel panel-default ins-three" data-collapsed="0">
          <!-- panel body -->
          <div class="panel-body ins-four">
            <p class="ins-four">
              {{ __('Purchase code verification is not required for Gigvora. You may provide a reference code or leave it blank.') }}
            </p>
            <br>
            <div class="row">
              <div class="col-md-12">
                <form method="POST" enctype="multipart/form-data" class="d-block ajaxForm" action="{{ route('install.validate') }}">
                  @csrf 
                  <div class="form-group">
                    <label class="control-label">{{ __('Purchase Code (optional)') }}</label>
                      <input type="text" class="form-control eForm-control" name="purchase_code" placeholder="Optional reference code" 
                        autofocus autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label class="control-label"></label>
                    <button type="submit" class="btn btn-primary">{{ __('Continue') }}</button>
                  </div>
                </form>
                <br>
                <p>
                  <strong>{{ __('Gigvora installations skip Envato license checks; continue to proceed.') }}</strong>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection