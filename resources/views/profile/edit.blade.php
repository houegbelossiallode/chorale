@extends('layouts.admin')

@section('page_title', 'Mon Profil')

@section('content')
    <div class="space-y-4 max-w-full">
        <!-- Header Area -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-[#444050]">Paramètres du Profil</h1>
                <p class="text-[14px] text-slate-400 font-medium">Gérez vos informations personnelles et la sécurité de
                    votre compte.</p>
            </div>
        </div>

        <!-- Update Profile Info -->
        <div class="card-material p-6 sm:p-8">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Update Password -->
            <div class="card-material p-6 sm:p-8 h-full">
                @include('profile.partials.update-password-form')
            </div>

            <!-- Delete Account -->
            <div class="card-material p-6 sm:p-8 border border-rose-100 h-full">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
@endsection