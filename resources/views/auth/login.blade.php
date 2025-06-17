@extends('layouts.master')

@section('title', 'Login')

@section('content')
<div class="min-h-screen bg-blue-900 flex flex-col items-center justify-center px-6 py-10">
    <h1 class="text-white text-2xl font-bold mb-6">Login</h1>

    <div class="bg-white w-full max-w-sm rounded-xl shadow-md p-6">
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <input name="email" type="text" placeholder="Email UB" class="w-full border border-blue-600 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600" required>
            <input name="password" type="password" placeholder="Password" class="w-full border border-blue-600 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600" required>

            <div class="flex items-center">
                <input type="checkbox" name="remember" class="mr-2">
                <label class="text-sm text-gray-700">Remember me</label>
            </div>

            <div class="text-sm text-gray-600">
                Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Register</a>
            </div>

            <button type="submit" class="w-full bg-[#002C6A] text-white rounded-full py-2 font-semibold hover:bg-blue-800"> 
                Masuk
            </button>
        </form>
    </div>
</div>
@endsection