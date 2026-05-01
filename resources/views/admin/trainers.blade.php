@extends('layouts.app')

@section('title', 'Manage Trainers')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">🏋️ Manage Trainers</h1>
    
    <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden border border-gray-700 shadow-lg">
        <div class="px-6 py-4 border-b border-gray-700">
            <h2 class="text-xl font-bold text-white">All Trainers</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Specialization</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Rate/hr</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach($trainers as $trainer)
                    <tr class="hover:bg-gray-700/30 transition">
                        <td class="px-6 py-3 font-medium text-gray-200">{{ $trainer->name }}</td>
                        <td class="px-6 py-3 text-gray-400">{{ $trainer->email }}</td>
                        <td class="px-6 py-3 text-gray-400">{{ $trainer->specialization ?? 'General' }}</td>
                        <td class="px-6 py-3 text-gray-400">₹{{ number_format($trainer->hourly_rate ?? 500) }}</td>
                        <td class="px-6 py-3">
                            @if($trainer->is_verified ?? false)
                                <span class="bg-green-900/50 text-green-300 px-2 py-1 rounded-full text-xs font-semibold">✅ Verified</span>
                            @else
                                <span class="bg-yellow-900/50 text-yellow-300 px-2 py-1 rounded-full text-xs font-semibold">⏳ Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex space-x-2 flex-wrap gap-1">
                                @if(!($trainer->is_verified ?? false))
                                    <form method="POST" action="{{ route('admin.trainers.verify', $trainer->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-600/80 text-white px-3 py-1 rounded text-xs hover:bg-green-500 transition">Verify</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.trainers.unverify', $trainer->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-yellow-600/80 text-white px-3 py-1 rounded text-xs hover:bg-yellow-500 transition">Unverify</button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.trainers.delete', $trainer->id) }}" class="inline" onsubmit="return confirm('Delete this trainer?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600/80 text-white px-3 py-1 rounded text-xs hover:bg-red-500 transition">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-700">
            {{ $trainers->links() }}
        </div>
    </div>
</div>
@endsection