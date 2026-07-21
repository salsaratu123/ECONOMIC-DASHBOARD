@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium mb-4">Pengontrol Tampilan User (Frontend CMS)</h3>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Judul Situs (Site Title)</label>
                <input type="text" name="site_title" value="{{ $settings['site_title'] }}" class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Teks Running Announcement / Alert User</label>
                <input type="text" name="announcement_bar" value="{{ $settings['announcement_bar'] }}" class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Heading Utama (Hero Section)</label>
                <input type="text" name="hero_heading" value="{{ $settings['hero_heading'] }}" class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Sub-Heading Utama</label>
                <textarea name="hero_subheading" rows="3" class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:border-blue-500">{{ $settings['hero_subheading'] }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">ShipFinder API Key (Live Marine)</label>
                <input type="text" name="shipfinder_key" value="{{ $settings['shipfinder_key'] }}" class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:border-blue-500">
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                Simpan Perubahan Tampilan
            </button>
        </form>
    </div>
</div>
@endsection