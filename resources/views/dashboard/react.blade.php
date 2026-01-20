@extends('dashboard.new-layout')

@section('content')
<div id="react-dashboard" data-props='@json($reactProps)' class="w-full"></div>
@endsection

@push('scripts')
    @viteReactRefresh
    @vite('resources/js/dashboard.jsx')
@endpush
