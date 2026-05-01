@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">👥 Manage Users</h1>
    
    <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden border border-gray-700 shadow-lg">
        <div class="px-6 py-4 border-b border-gray-700">
            <h2 class="text-xl font-bold text-white">All Users</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-700/30 transition">
                        <td class="px-6 py-3 font-medium text-gray-200">{{ $user->name }}</td>
                        <td class="px-6 py-3 text-gray-400">{{ $user->email }}</td>
                        <td class="px-6 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold 
                            @if($user->role == 'admin') bg-purple-900/50 text-purple-300
                            @elseif($user->role == 'trainer') bg-blue-900/50 text-blue-300
                            @else bg-green-900/50 text-green-300 @endif">
                            {{ ucfirst($user->role ?? 'trainee') }}
                        </span>
                        </td>
                        <td class="px-6 py-3 text-gray-400">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-3">
                            @if($user->is_blocked ?? false)
                                <span class="text-red-400 text-sm">Blocked</span>
                            @else
                                <span class="text-green-400 text-sm">Active</span>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex space-x-2">
                                @if($user->role != 'admin')
                                    <form method="POST" action="{{ route('admin.users.block', $user->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-yellow-400 hover:text-yellow-300 text-sm transition">
                                            {{ ($user->is_blocked ?? false) ? 'Unblock' : 'Block' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.delete', $user->id) }}" class="inline" onsubmit="return confirm('Delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 text-sm transition">Delete</button>
                                    </form>
                                @else
                                    <span class="text-gray-500 text-sm">Admin</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-700">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection