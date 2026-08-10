@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Create User</h1>
    <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
        @csrf
        <input type="text" name="name" placeholder="Name" class="w-full p-2 border rounded">
        <input type="email" name="email" placeholder="Email" class="w-full p-2 border rounded">
        <div class="relative">
            <button type="button" class="js-toggle-password absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-400 hover:text-zinc-600"
                tabindex="-1" data-target="create-user-password">
                <svg class="js-eye-icon w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51
                       7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431
                       0 .638C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </button>
            <input type="password" id="create-user-password" name="password" placeholder="Password" class="w-full p-2 pr-12 border rounded">
        </div>
        <select name="role" class="w-full p-2 border rounded">
            <option value="user">User</option>
            <option value="admin">Admin</option>
            <option value="superadmin">Super Admin</option>
        </select>
        <button type="submit" class="bg-[var(--accent)] text-white px-4 py-2 rounded">Save</button>
    </form>
@endsection
