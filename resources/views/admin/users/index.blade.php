@extends('layouts.aethryna')

@section('title', 'User Management | Skills Co-op')

@section('content')

@include('admin._nav')
@include('admin._flash')
    <!-- Header -->
    <section class="bg-gradient-to-r from-teal-700 to-teal-900 text-white py-16 px-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-400 opacity-10 rounded-full -mr-48 -mt-48"></div>
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center relative z-10 gap-8">
            <div>
                <h1 class="text-3xl font-bold mb-2">User Management</h1>
                <p class="text-lg opacity-90">Manage registered users and administrators</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-white text-teal-800 px-6 py-3 rounded-full font-bold hover:bg-teal-50 transition-colors shadow-lg">
                <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>
    </section>

    <!-- Users List -->
    <section class="bg-gray-50 py-12 px-8">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex flex-wrap justify-between items-center gap-4">
                    <h3 class="text-xl font-bold text-teal-700">Registered Users</h3>
                    <div class="flex items-center gap-3">
                        <form method="GET" action="{{ route('admin.users') }}" class="flex items-center gap-2">
                            <label for="q" class="sr-only">Search users</label>
                            <input type="search" id="q" name="q" value="{{ $q }}" placeholder="Name or email"
                                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <button type="submit" class="bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-semibold">Search</button>
                            @if ($q !== '')
                                <a href="{{ route('admin.users') }}" class="text-sm text-gray-500 hover:text-teal-700">Clear</a>
                            @endif
                        </form>
                        <span class="bg-teal-100 text-teal-800 text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap">
                            {{ $q !== '' ? 'Found' : 'Total' }}: {{ $users->total() }}
                        </span>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                                <th class="p-4 border-b border-gray-200 font-semibold">Name</th>
                                <th class="p-4 border-b border-gray-200 font-semibold">Email</th>
                                <th class="p-4 border-b border-gray-200 font-semibold">Role</th>
                                <th class="p-4 border-b border-gray-200 font-semibold">Joined</th>
                                <th class="p-4 border-b border-gray-200 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @forelse($users as $user)
                                <tr class="hover:bg-teal-50 transition-colors border-b border-gray-100 last:border-0">
                                    <td class="p-4 font-medium text-teal-900">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-teal-500 flex items-center justify-center text-white text-sm font-bold">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    <td class="p-4">{{ $user->email }}</td>
                                    <td class="p-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                            {{ $user->is_admin ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $user->is_admin ? 'Admin' : 'User' }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-sm text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                                    <td class="p-4 text-right">
                                        <div class="flex items-center justify-end">
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors mx-1" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-gray-500">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-gray-100">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
