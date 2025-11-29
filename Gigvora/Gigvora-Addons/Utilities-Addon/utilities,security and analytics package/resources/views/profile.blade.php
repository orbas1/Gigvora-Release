@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('pro-network::pro_network_utilities_security_analytics.profile_title') }}</h1>
    <form method="post" action="{{ url('pro-network/profile') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">{{ __('pro-network::pro_network_utilities_security_analytics.headline') }}</label>
            <input name="headline" class="form-control" />
        </div>
        <button class="btn btn-primary">{{ __('pro-network::pro_network_utilities_security_analytics.save') }}</button>
    </form>
</div>
@endsection
