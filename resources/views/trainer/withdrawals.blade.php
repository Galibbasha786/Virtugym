@extends('layouts.app')

@section('title', 'Withdrawals')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-2 bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">💰 Withdrawals</h1>
    <p class="text-gray-400 mb-8">Request payouts for your earnings</p>
    
    <!-- Balance Cards -->
    <div class="grid md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-r from-green-900/40 to-green-800/20 rounded-xl p-6 border border-green-500/30 shadow-lg">
            <p class="text-gray-400 text-sm">Total Earnings</p>
            <p class="text-3xl font-bold text-green-400">₹{{ number_format($totalEarnings ?? 0) }}</p>
        </div>
        <div class="bg-gradient-to-r from-red-900/40 to-red-800/20 rounded-xl p-6 border border-red-500/30 shadow-lg">
            <p class="text-gray-400 text-sm">Total Withdrawn</p>
            <p class="text-3xl font-bold text-red-400">₹{{ number_format($totalWithdrawn ?? 0) }}</p>
        </div>
        <div class="bg-gradient-to-r from-purple-900/40 to-purple-800/20 rounded-xl p-6 border border-purple-500/30 shadow-lg">
            <p class="text-gray-400 text-sm">Available Balance</p>
            <p class="text-3xl font-bold text-purple-400">₹{{ number_format($availableBalance ?? 0) }}</p>
        </div>
    </div>
    
    <!-- Request Withdrawal Form -->
    <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 mb-8 border border-gray-700 shadow-lg">
        <h2 class="text-xl font-bold mb-4 text-white">Request Withdrawal</h2>
        @if(isset($hasPending) && $hasPending)
        <div class="bg-yellow-900/40 rounded-lg p-4 text-center border border-yellow-700">
            <p class="text-yellow-400">You already have a pending withdrawal request. Please wait for the admin to process it.</p>
        </div>
        @elseif(isset($availableBalance) && $availableBalance >= 100)
        <form method="POST" action="{{ route('trainer.withdrawal.request') }}">
            @csrf
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-300 font-semibold mb-2">Amount (₹)</label>
                    <input type="number" name="amount" step="100" min="100" max="{{ $availableBalance }}" required 
                           class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500">
                    <p class="text-gray-500 text-xs mt-1">Min: ₹100 | Max: ₹{{ number_format($availableBalance) }}</p>
                </div>
                <div>
                    <label class="block text-gray-300 font-semibold mb-2">UPI ID</label>
                    <input type="text" name="upi_id" placeholder="username@upi" required 
                           class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500">
                </div>
            </div>
            <button type="submit" class="mt-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-2 rounded-lg hover:shadow-lg transition transform hover:scale-105">
                Request Withdrawal
            </button>
        </form>
        @else
        <div class="bg-gray-700/50 rounded-lg p-4 text-center border border-gray-600">
            <p class="text-gray-400">You need a minimum balance of <span class="text-purple-400 font-bold">₹100</span> to request a withdrawal.</p>
        </div>
        @endif
    </div>
    
    <!-- Withdrawal History -->
    <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden border border-gray-700 shadow-lg">
        <h2 class="text-xl font-bold p-6 border-b border-gray-700 text-white">Withdrawal History</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">UPI ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($requests as $req)
                    <tr class="hover:bg-gray-700/30 transition">
                        <td class="px-6 py-3 text-gray-300">{{ \Carbon\Carbon::parse($req->created_at)->format('M d, Y') }}</td>
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
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            No withdrawal requests yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection