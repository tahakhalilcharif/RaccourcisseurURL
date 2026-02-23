<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lien non valide — URL Shortener</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="text-center px-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-3">
            🔗 Ce lien n'est plus valide
        </h1>


        <p class="text-gray-500 mb-8 max-w-md mx-auto">
            Le lien que vous avez suivi a été supprimé ou n'a jamais existé.
            Si vous pensez qu'il s'agit d'une erreur, contactez la personne qui vous a partagé ce lien.
        </p>

        <a href="{{ url('/') }}"
           class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition duration-150 ease-in-out">
            Retour à l'accueil
        </a>
    </div>
</body>
</html>
