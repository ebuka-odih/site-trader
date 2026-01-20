@extends('admin.layouts.app')

@section('content')
<div class="p-4 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Referral Activity</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Track who invited whom and the status of each referral.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">Referrer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">Referred User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">Reward</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    @forelse($referrals as $referral)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">#{{ $referral->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                <div>{{ $referral->referrer?->name ?? 'Deleted user' }}</div>
                                <div class="text-xs text-gray-500">{{ $referral->referrer?->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                <div>{{ $referral->referredUser?->name ?? 'Deleted user' }}</div>
                                <div class="text-xs text-gray-500">{{ $referral->referredUser?->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                {{ $referral->reward_amount > 0 ? '$' . number_format($referral->reward_amount, 2) : '—' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium 
                                    {{ $referral->status === 'rewarded' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($referral->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $referral->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                No referrals recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $referrals->links() }}
        </div>
    </div>
</div>
@endsection
