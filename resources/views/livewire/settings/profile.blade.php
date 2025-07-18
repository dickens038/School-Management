<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public $photo;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
            'photo' => ['nullable', 'image', 'max:1024'], // 1MB max
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Handle profile photo upload
        if ($this->photo) {
            try {
                // Delete old photo if exists
                if ($user->profile_photo) {
                    Storage::disk('public')->delete($user->profile_photo);
                }

                // Store new photo
                $path = $this->photo->store('profile-photos', 'public');
                $user->profile_photo = $path;
                
                // Debug: Log the upload
                \Log::info('Profile photo uploaded', [
                    'user_id' => $user->id,
                    'path' => $path,
                    'full_path' => Storage::disk('public')->path($path)
                ]);
            } catch (\Exception $e) {
                \Log::error('Profile photo upload failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Remove the current user's profile photo.
     */
    public function removeProfilePhoto(): void
    {
        $user = Auth::user();

        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
            $user->profile_photo = null;
            $user->save();
        }

        $this->dispatch('profile-updated', name: $user->name);
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your name, email address, and profile photo')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <!-- Debug Info -->
            @if($photo)
                <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    File selected: {{ $photo->getClientOriginalName() }}
                </div>
            @endif
            
            @error('photo')
                <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ $message }}
                </div>
            @enderror

            <!-- Profile Photo Section -->
            <div class="space-y-4">
                <flux:text class="text-sm font-medium">{{ __('Profile Photo') }}</flux:text>
                
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <span class="relative flex h-16 w-16 shrink-0 overflow-hidden rounded-full">
                            @if(auth()->user()->profilePhotoUrl())
                                <img
                                    src="{{ auth()->user()->profilePhotoUrl() }}"
                                    alt="{{ auth()->user()->name }}"
                                    class="h-full w-full object-cover rounded-full"
                                />
                            @else
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-full bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white text-lg font-semibold"
                                >
                                    {{ auth()->user()->initials() }}
                                </span>
                            @endif
                        </span>
                    </div>
                    
                    <div class="flex-1">
                        <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Upload Photo') }}
                        </label>
                        <input 
                            wire:model="photo" 
                            type="file" 
                            id="photo"
                            accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300"
                        />
                        <flux:text class="text-xs text-gray-500 mt-1">
                            {{ __('Upload a new profile photo. Maximum size: 1MB.') }}
                        </flux:text>
                        
                        @if(auth()->user()->profilePhotoUrl())
                            <div class="mt-2">
                                <flux:button 
                                    wire:click="removeProfilePhoto" 
                                    variant="secondary" 
                                    size="sm"
                                    class="text-red-600 hover:text-red-700"
                                >
                                    {{ __('Remove Photo') }}
                                </flux:button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
