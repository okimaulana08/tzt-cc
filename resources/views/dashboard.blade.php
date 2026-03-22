<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Welcome card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                    Welcome back, {{ Auth::user()->name }}!
                </h3>
                <p class="text-sm text-gray-500">
                    You are logged in as
                    @foreach (Auth::user()->getRoleNames() as $role)
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">{{ $role }}</span>
                    @endforeach
                </p>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                    <p class="text-3xl font-bold text-indigo-600">{{ \App\Models\Post::count() }}</p>
                    <p class="text-sm text-gray-500 mt-1">Total Posts</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                    <p class="text-3xl font-bold text-green-600">{{ \App\Models\Post::where('status', 'published')->count() }}</p>
                    <p class="text-sm text-gray-500 mt-1">Published</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                    <p class="text-3xl font-bold text-yellow-500">{{ \App\Models\Post::where('status', 'draft')->count() }}</p>
                    <p class="text-sm text-gray-500 mt-1">Drafts</p>
                </div>
            </div>

            {{-- Quick links --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-4">Quick Links</h4>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('posts.index') }}"
                       class="px-4 py-2 bg-indigo-50 text-indigo-700 text-sm rounded-md hover:bg-indigo-100 transition font-medium">
                        View All Posts
                    </a>
                    @can('create', \App\Models\Post::class)
                        <a href="{{ route('posts.create') }}"
                           class="px-4 py-2 bg-green-50 text-green-700 text-sm rounded-md hover:bg-green-100 transition font-medium">
                            Create New Post
                        </a>
                    @endcan
                    <a href="{{ route('profile.edit') }}"
                       class="px-4 py-2 bg-gray-50 text-gray-700 text-sm rounded-md hover:bg-gray-100 transition font-medium">
                        My Profile
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
