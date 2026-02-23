<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard.index') }}"
               class="text-gray-500 hover:text-gray-700 text-sm">← Retour</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ajouter un lien') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <form method="POST" action="{{ route('dashboard.store') }}">
                        @csrf

                        <div class="mb-6">
                            <x-input-label for="url_originale" :value="__('URL à raccourcir')" />
                            <x-text-input
                                id="url_originale"
                                name="url_originale"
                                type="url"
                                class="mt-1 block w-full"
                                placeholder="https://exemple.com/un-lien-tres-long"
                                :value="old('url_originale')"
                                required
                                autofocus
                            />
                            <x-input-error :messages="$errors->get('url_originale')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>
                                {{ __('Raccourcir') }}
                            </x-primary-button>
                            <a href="{{ route('dashboard.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                                Annuler
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
