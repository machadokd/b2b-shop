<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — B2B Shop</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%); min-height: 100vh;"
      class="d-flex align-items-center justify-content-center py-5">

    <div style="width: 100%; max-width: 420px; padding: 0 1rem;">
        <div class="auth-brand">
            <div class="auth-brand-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
            </div>
            <h1>B2B Shop</h1>
            <p>Plataforma de Comércio B2B</p>
        </div>

        <div class="card shadow" style="border-radius: 1rem; border: none;">
            <div class="card-body p-4 p-md-5">
                @yield('content')
            </div>
        </div>
    </div>

</body>
</html>
