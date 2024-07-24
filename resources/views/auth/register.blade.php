@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>{{ __('Registrar Usuario') }}</h1>
@stop

@section('content')
<style>
    nav[x-data="{ open: false }"] {
           display: none;
       }
   </style>
<x-app-layout>
    <form method="POST" action="{{ route('register.store') }}">
        @csrf
        <!-- Rut -->
        <div>
            <x-input-label for="rut" :value="__('Rut')" />
            <x-text-input for="rut" class="block mt-1 w-full" type="text" name="rut" :value="old('rut')" required autofocus autocomplete="rut" placeholder="11111111-1"/>
            <x-input-error :messages="$errors->get('rut')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="descripcion" :value="__('Descripción')" />
            <x-text-input id="descripcion" class="block mt-1 w-full" type="text" name="descripcion" :value="old('descripcion')" required  autocomplete="descripcion"/>
        </div>
        <!-- Name -->
        <div class="mt-4">
            <x-input-label for="name" :value="__('Nombre Usuario')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

         <!-- Role Selection -->
         <div class="mt-4">
            <x-input-label for="role_id" :value="__('Rol')" />
            <select id="role_id" name="role_id" class="block mt-1 w-full">
                <option value="">{{ __('Seleccione un rol') }}</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ms-4">
                {{ __('Registrar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
@stop