@extends('dashboard.new-layout')

@section('content')
<div id="react-trading-page" data-props='@json($reactProps)' class="w-full h-full"></div>
@endsection

@push('scripts')
    @viteReactRefresh
    @vite('resources/js/trading.jsx')
@endpush
















