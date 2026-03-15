<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate(); 

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
     <form class="card card-md" wire:submit="login">
          <div class="card-body"> 
            <h2 class="mb-5 text-center">Login to your account</h2>

            <div class="mb-3">
              <!-- <label class="form-label">Email address</label>
              <input type="email" class="form-control" placeholder="####.####@philsinter.com.ph" autocomplete="off"> -->
                 <!-- Email Address -->
            
                <x-input-label class="form-label" for="email" :value="__('Email address')" />
                <x-text-input wire:model="form.email" id="email" class="form-control" type="email" name="email" required autofocus autocomplete="off" placeholder="####.####@philsinter.com.ph" />
                <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        

            </div>
            <div class="mb-2">
              <!-- <label class="form-label">
                Password
                {{-- <span class="form-label-description">
                  <a href="./forgot-password.html">I forgot password</a>
                </span> --}}
              </label> -->
               <x-input-label  class="form-label" for="password" :value="__('Password')" />

              <div class="input-group input-group-flat">
                 
               
                <x-text-input wire:model="form.password" id="password"  class="form-control"  placeholder="Password" 
                                type="password"
                                name="password"
                                required autocomplete="off" />
                <span class="input-group-text">
                  <a href="#" class="link-secondary" title="Show password" data-toggle="tooltip">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"/><circle cx="12" cy="12" r="2" /><path d="M2 12l1.5 2a11 11 0 0 0 17 0l1.5 -2" /><path d="M2 12l1.5 -2a11 11 0 0 1 17 0l1.5 2" /></svg>
                  </a>
                </span>
                <x-input-error :messages="$errors->get('form.password')" class="mt-2" />




              </div>
            </div>

             
            <div class="form-footer">
            
                <x-primary-button class="btn btn-primary btn-block">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
          </div>
          <div class="hr-text"></div>
          <div class="card-body">
        
          </div>
        </form>
    <form wire:submit="login">
        

     

        <div class="flex items-center justify-end mt-4">
         

        
        </div>
    </form>
</div>
