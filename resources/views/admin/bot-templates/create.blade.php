@extends('admin.layouts.app')

@section('content')
<div class="p-4 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Bot Template</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Preconfigure a strategy for traders to copy.</p>
        </div>
        <a href="{{ route('admin.bot-templates.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300">Back to Templates</a>
    </div>

    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700">
                <p class="font-semibold mb-2">Please fix the following:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.bot-templates.store') }}" class="space-y-8">
            @csrf
            @include('admin.bot-templates.partials.form')
            <div class="flex flex-col sm:flex-row sm:justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.bot-templates.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200">Cancel</a>
                <button type="submit" class="inline-flex items-center justify-center px-6 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Save Template</button>
            </div>
        </form>
    </div>
</div>
@endsection
