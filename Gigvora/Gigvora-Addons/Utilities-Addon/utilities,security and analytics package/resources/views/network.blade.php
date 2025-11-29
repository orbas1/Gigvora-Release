@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('pro-network::pro_network_utilities_security_analytics.network_title') }}</h1>
    <div id="pro-network-list" data-endpoint="{{ url('pro-network/network') }}"></div>
</div>
@endsection
