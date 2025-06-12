<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <style>
            [x-cloak] { display: none !important; }
        </style>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <script>
            document.querySelectorAll('.btn-qr').forEach(btn => {
                btn.addEventListener('click', function(){
                    const entity = this.getAttribute('data-entity');
                    const img = document.getElementById('qrCodeImageTag');
                    fetch(`/qrcode/${entity}`)
                        .then(res => res.text())
                        .then(svg => {
                            img.src = svg;
                            img.style.display = "block";
                            document.getElementById('qrModal').classList.remove('hidden');
                            // Dispara evento customizado para Alpine exibir o modal
                            //window.dispatchEvent(new CustomEvent('open-qrcode', { detail: { svg } }));
                        });

                    // img.src = `/qrcode/${entity}?${Date.now()}`;
                    // img.style.display = "block";
                    // document.getElementById('qrModal').classList.remove('hidden');
                });
            });
        </script>

        <script>
            function maskPhone(input){
                input.addEventListener('input', function(){
                    let value = input.value.replace(/\D/g, ''); // Remove não dígitos
                    if (value.length > 11) value = value.slice(0, 11);

                    if (value.length > 10) {
                        // Formato: (99) 99999-9999
                        value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
                    } else if (value.length > 6) {
                        // Formato: (99) 9999-9999
                        value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
                    } else if (value.length > 2) {
                        // Formato: (99) 9999
                        value = value.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
                    } else {
                        // Formato: (99
                        value = value.replace(/^(\d*)/, '($1');
                    }
                    input.value = value;
                });
            }

            // Cadastro
            document.addEventListener('DOMContentLoaded', function(){
                const telCreate = document.getElementById('telefoneCreate');
                if (telCreate) maskPhone(telCreate);

                // Edição - como o input pode nascer a cada abertura do modal, use delegação:
                document.addEventListener('click', function(){
                    setTimeout(function(){
                        const telEdit = document.getElementById('telefoneEdit');
                        if (telEdit) maskPhone(telEdit);
                    }, 100);
                });
            });
        </script>






    </body>
</html>
