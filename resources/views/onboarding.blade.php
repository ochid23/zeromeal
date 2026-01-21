@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-lg">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Selamat Datang di ZeroMeal!
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Bantu kami mengenal Anda lebih baik dengan menjawab 3 pertanyaan singkat ini.
            </p>
        </div>
        <form class="mt-8 space-y-6" action="{{ route('onboarding.store') }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label for="question1" class="block text-sm font-medium text-gray-700">1. Dari mana Anda mengetahui ZeroMeal?</label>
                    <select id="question1" name="source" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                        <option value="" disabled selected>Pilih salah satu</option>
                        <option value="Social Media">Media Sosial (Instagram, TikTok, dll)</option>
                        <option value="Teman/Keluarga">Rekomendasi Teman/Keluarga</option>
                        <option value="Iklan">Iklan Online</option>
                        <option value="Search Engine">Pencarian Google</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div>
                    <label for="question2" class="block text-sm font-medium text-gray-700">2. Apa tujuan utama Anda menggunakan aplikasi ini?</label>
                    <select id="question2" name="goal" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                        <option value="" disabled selected>Pilih salah satu</option>
                        <option value="Mengurangi Sampah Makanan">Mengurangi Sampah Makanan</option>
                        <option value="Menghemat Pengeluaran">Menghemat Pengeluaran</option>
                        <option value="Mencari Inspirasi Masak">Mencari Inspirasi Masak</option>
                        <option value="Manajemen Stok">Manajemen Stok Dapur</option>
                    </select>
                </div>

                <div>
                    <label for="question3" class="block text-sm font-medium text-gray-700">3. Berapa kali Anda memasak dalam seminggu?</label>
                    <select id="question3" name="cooking_frequency" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                        <option value="" disabled selected>Pilih salah satu</option>
                        <option value="Jarang (0-2 kali)">Jarang (0-2 kali)</option>
                        <option value="Kadang-kadang (3-5 kali)">Kadang-kadang (3-5 kali)</option>
                        <option value="Sering (Setiap hari)">Sering (Setiap hari)</option>
                    </select>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Selesai & Masuk Dashboard
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
