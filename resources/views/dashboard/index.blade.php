<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tableau de bord') }}
            </h2>
            <a href="{{ route('dashboard.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150">
                + Ajouter un lien
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Messages flash --}}
            @if (session('succes'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-md">
                    {{ session('succes') }}
                </div>
            @endif
            @if (session('erreur'))
                <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-md">
                    {{ session('erreur') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($liens->isEmpty())
                        <p class="text-gray-500 text-center py-8">
                            Vous n'avez pas encore de liens raccourcis.
                            <a href="{{ route('dashboard.create') }}" class="text-indigo-600 hover:underline">Créez-en un !</a>
                        </p>
                    @else
                        <div class="overflow-x-auto">      
                            <x-primary-button class="mb-4">
                                <a href="{{ route('dashboard.create') }}" class="text-white">Ajouter un lien raccourci</a>
                            </x-primary-button>
                            <table class="min-w-full divide-y divide-gray-200 text-sm" id="tableau-liens">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL courte</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL originale</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Visites</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Dernière visite</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($liens as $lien)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ url('/' . $lien->code_court) }}" target="_blank">
                                                        <span class="font-mono text-indigo-600 url-courte"
                                                            data-url="{{ url('/' . $lien->code_court) }}">
                                                            {{ url('/' . $lien->code_court) }}
                                                        </span>
                                                    </a>

                                                    <button type="button"
                                                            class="btn-copier text-xs px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded border border-gray-300 transition"
                                                            data-url="{{ url('/' . $lien->code_court) }}"
                                                            title="Copier dans le presse-papier">
                                                        📋 Copier
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <a href="{{ $lien->url_originale }}" target="_blank"
                                                   class="text-gray-700 hover:text-indigo-600 truncate block max-w-xs" title="{{ $lien->url_originale }}">
                                                    {{ Str::limit($lien->url_originale, 60) }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    {{ $lien->nombre_visites }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    {{ $lien->derniere_visite_le ?? 'Pas encore visité' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                                <a href="{{ route('dashboard.edit', $lien) }}"
                                                   class="text-indigo-600 hover:text-indigo-900 mr-3 font-medium">Modifier</a>

                                                <form action="{{ route('dashboard.destroy', $lien) }}" method="POST"
                                                      class="inline-block"
                                                      onsubmit="return confirm('Supprimer ce lien définitivement ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-600 hover:text-red-900 font-medium">Supprimer</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-4">
                            {{ $liens->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(function () {
            $('.btn-copier').on('click', function () {
                var url = $(this).data('url');
                var btn  = $(this);

                // Utiliser l'API Clipboard moderne
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(url).then(function () {
                        btn.text('✅ Copié !').addClass('bg-green-100 border-green-400');
                        setTimeout(function () {
                            btn.text('📋 Copier').removeClass('bg-green-100 border-green-400');
                        }, 2000);
                    });
                } else {
                    // Fallback pour les contextes non-sécurisés
                    var $temp = $('<input>');
                    $('body').append($temp);
                    $temp.val(url).select();
                    document.execCommand('copy');
                    $temp.remove();
                    btn.text('✅ Copié !').addClass('bg-green-100 border-green-400');
                    setTimeout(function () {
                        btn.text('📋 Copier').removeClass('bg-green-100 border-green-400');
                    }, 2000);
                }
            });
        });
    </script>
</x-app-layout>
