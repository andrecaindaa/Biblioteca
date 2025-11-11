<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-base-200 min-h-screen">
    <div class="navbar bg-primary text-primary-content">
        <div class="flex-1 px-2 text-xl font-bold">📚 Biblioteca</div>
        <div class="flex-none">
            <ul class="menu menu-horizontal px-1">
                <li><a href="/">Início</a></li>
                <li><a href="/livros">Livros</a></li>
                <li><a href="/autores">Autores</a></li>
                <li><a href="/editoras">Editoras</a></li>
            </ul>
        </div>
    </div>

    <div class="p-6">
        @yield('content')
    </div>
</body>
</html>
