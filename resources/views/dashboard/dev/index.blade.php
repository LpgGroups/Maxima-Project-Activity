@extends('dashboard.layouts.dashboardmain')

@section('container')
    <x-welcome-comp :name="Auth::user()->name" variant="info" class="mt-2" />
    <div class="overflow-x-auto mt-6">
        <div class="flex flex-nowrap gap-6 px-2">

            <!-- Manage Account -->
            <a href="{{ route('dashboard.dev.alluser') }}"
                class="min-w-[250px] bg-white shadow-lg rounded-lg p-6 text-center hover:shadow-xl hover:bg-gray-100 transition duration-300">
                <h3 class="text-lg font-semibold mb-2">Manage Account</h3>
                <p class="text-sm text-gray-600">Kelola akun pengguna dan hak akses.</p>
            </a>

            <!-- Manage Training -->
            <a href="{{ route('training.index') }}"
                class="min-w-[250px] bg-white shadow-lg rounded-lg p-6 text-center hover:shadow-xl hover:bg-gray-100 transition duration-300">
                <h3 class="text-lg font-semibold mb-2">Manage Training</h3>
                <p class="text-sm text-gray-600">Atur jadwal dan materi pelatihan.</p>
            </a>


            <!-- Manage Poster -->
            <a href="{{ route('carrousel.index') }}"
                class="min-w-[250px] bg-white shadow-lg rounded-lg p-6 text-center hover:shadow-xl hover:bg-gray-100 transition duration-300">
                <h3 class="text-lg font-semibold mb-2">Manage Poster</h3>
                <p class="text-sm text-gray-600">Unggah dan kelola poster informasi.</p>
            </a>

        </div>
    </div>
@endsection
