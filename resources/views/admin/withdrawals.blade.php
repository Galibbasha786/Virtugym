@extends('layouts.app')

@section('title', 'Manage Withdrawals')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">💰 Manage Withdrawals</h1>
    
    <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden border border-gray-700 shadow-lg">
        <h2 class="text-xl font-bold p-6 border-b border-gray-700 text-white">Withdrawal Requests</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Trainer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">UPI ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($requests as $req)
                    <tr class="hover:bg-gray-700/30 transition">
                        <td class="px-6 py-3 text-gray-400">{{ \Carbon\Carbon::parse($req->created_at)->format('M d, Y') }}</td>
                        <td class="px-6 py-3 text-gray-200 font-medium">{{ $req->trainer->name ?? 'Unknown' }}</td>
                        <td class="px-6 py-3 font-bold text-purple-400">₹{{ number_format($req->amount) }}</td>
                        <td class="px-6 py-3 text-gray-300">{{ $req->upi_id }}</td>
                        <td class="px-6 py-3">
                            @if($req->status == 'pending')
                                <span class="bg-yellow-900/50 text-yellow-300 px-2 py-1 rounded-full text-xs font-semibold">⏳ Pending</span>
                            @elseif($req->status == 'completed')
                                <span class="bg-green-900/50 text-green-300 px-2 py-1 rounded-full text-xs font-semibold">✅ Completed</span>
                            @else
                                <span class="bg-red-900/50 text-red-300 px-2 py-1 rounded-full text-xs font-semibold">❌ Rejected</span>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            @if($req->status == 'pending')
                            <div class="flex space-x-2">
                                <form method="POST" action="{{ route('admin.withdrawals.approve', $req->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-600/80 text-white px-3 py-1 rounded text-xs hover:bg-green-500 transition">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.withdrawals.reject', $req->id) }}" class="inline" onsubmit="return confirm('Reject this withdrawal request?')">
                                    @csrf
                                    <button type="submit" class="bg-red-600/80 text-white px-3 py-1 rounded text-xs hover:bg-red-500 transition">Reject</button>
                                </form>
                            </div>
                            @else
                            <span class="text-gray-500 text-sm">Processed</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            No withdrawal requests found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-700">
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection