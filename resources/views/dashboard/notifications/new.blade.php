@extends('dashboard.new-layout')

@section('content')
<div id="react-notifications" data-props='@json($reactProps)' class="w-full"></div>
@endsection

@push('scripts')
    @viteReactRefresh
    @vite('resources/js/notifications.jsx')
@endpush















