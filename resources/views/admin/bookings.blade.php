@extends('layouts.app')

@section('title', 'Manage Bookings')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">📅 Manage Bookings</h1>
    
    <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden border border-gray-700 shadow-lg">
        <div class="px-6 py-4 border-b border-gray-700">
            <h2 class="text-xl font-bold text-white">All Bookings</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Trainee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Trainer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach($bookings as $booking)
                    <tr class="hover:bg-gray-700/30 transition">
                        <td class="px-6 py-3 font-medium text-gray-200">{{ $booking->trainee->name ?? 'N/A' }}</td>
                        <td class="px-6 py-3 text-gray-400">{{ $booking->trainer->name ?? 'N/A' }}</td>
                        <td class="px-6 py-3 text-gray-400">{{ \Carbon\Carbon::parse($booking->session_date)->format('M d, Y h:i A') }}</td>
                        <td class="px-6 py-3 text-gray-400">{{ $booking->duration_minutes ?? 60 }} min</td>
                        <td class="px-6 py-3 font-semibold text-purple-400">₹{{ number_format($booking->amount ?? 0) }}</td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                @if($booking->status == 'confirmed') bg-green-900/50 text-green-300
                                @elseif($booking->status == 'completed') bg-blue-900/50 text-blue-300
                                @elseif($booking->status == 'cancelled') bg-red-900/50 text-red-300
                                @else bg-gray-700 text-gray-300 @endif">
                            {{ ucfirst($booking->status ?? 'pending') }}
                        </span>
                    </td>
                    <td class="px-6 py-3">
                        <form method="POST" action="{{ route('admin.bookings.delete', $booking->id) }}" onsubmit="return confirm('Delete this booking?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 text-sm transition">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-700">
        {{ $bookings->links() }}
    </div>
</div>
@endsection