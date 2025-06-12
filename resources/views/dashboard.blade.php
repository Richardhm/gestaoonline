<x-app-layout>
    <div class="flex justify-center gap-8 mt-12">

        @if(session('msg'))
            <div class="mt-4 bg-green-100 text-green-800 px-4 py-2 rounded">{{ session('msg') }}</div>
        @endif
        @if(session('erro'))
            <div class="mt-4 bg-red-100 text-red-800 px-4 py-2 rounded">{{ session('erro') }}</div>
        @endif



        <!-- Card AC -->
        <div class="bg-white shadow rounded-lg p-8 flex flex-col items-center w-72">
            <h2 class="text-xl font-bold mb-2">Autoridade Certificadora (AC)</h2>
            <a href="{{ route('acs.index') }}"
               class="rounded bg-blue-600 text-white px-3 py-2 mt-2 mb-4 hover:bg-blue-700 transition">Acessar CRUD</a>
            <button data-entity="acs" data-url="{{ route('acs.index') }}"
                    class="bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600 transition btn-qr">
                Gerar QRCode
            </button>
        </div>

        <!-- Card AC N2 -->
        <div class="bg-white shadow rounded-lg p-8 flex flex-col items-center w-72">
            <h2 class="text-xl font-bold mb-2">AC Nível 2 (AC N2)</h2>
            <a href="{{ route('acn2s.index') }}"
               class="rounded bg-blue-600 text-white px-3 py-2 mt-2 mb-4 hover:bg-blue-700 transition">Acessar CRUD</a>
            <button data-entity="acn2s" data-url="{{ route('acn2s.index') }}"
                    class="bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600 transition btn-qr">
                Gerar QRCode
            </button>
        </div>

        <!-- Card AR -->
        <div class="bg-white shadow rounded-lg p-8 flex flex-col items-center w-72">
            <h2 class="text-xl font-bold mb-2">Autoridade de Registro (AR)</h2>
            <a href="{{ route('ars.index') }}"
               class="rounded bg-blue-600 text-white px-3 py-2 mt-2 mb-4 hover:bg-blue-700 transition">Acessar CRUD</a>
            <button data-entity="ars" data-url="{{ route('ars.index') }}"
                    class="bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600 transition btn-qr">
                Gerar QRCode
            </button>
        </div>

        <div
            x-data="{ open: false, svg: '' }"
            x-on:open-qrcode.window="svg = $event.detail.svg; open = true"
            x-show="open"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 z-50"
            style="display: none"
        >
            <div class="bg-white rounded shadow-lg max-w-md w-full p-6 relative flex flex-col items-center">
                <button x-on:click="open=false" class="absolute right-2 top-2 text-gray-400 hover:text-gray-600">&times;</button>
                <h2 class="text-lg font-bold mb-2">QR Code</h2>
                <div class="w-48 h-48 mb-4" x-html="svg"></div>
            </div>
        </div>

        <div id="qrModal" class="fixed left-0 top-0 w-full h-full bg-black bg-opacity-60 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded shadow flex flex-col items-center">
                <button onclick="document.getElementById('qrModal').classList.add('hidden')" class="self-end text-2xl">&times;</button>
                <h2 class="text-lg font-bold mb-2">QR Code</h2>
                <img id="qrCodeImageTag" src="" class="mx-auto mb-4" alt="QR Code">
            </div>
        </div>





    </div>

    <div x-data="{ showImportModal: false }">
        <button
            @click="showImportModal = true"
            class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800 shadow mb-4"
        >
            Importar JSON
        </button>

        <!-- Modal de upload JSON -->
        <div
            x-show="showImportModal"
            x-cloak
            class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
        >
            <div
                @click.away="showImportModal = false"
                class="bg-white rounded-lg shadow-lg p-8 min-w-[350px] relative"
            >
                <button
                    @click="showImportModal = false"
                    class="absolute top-2 right-3 text-gray-500 hover:text-red-500 text-2xl"
                    title="Fechar"
                >&times;</button>
                <h2 class="text-xl font-bold mb-5 text-center">Importar Dados (JSON)</h2>
                <form
                    action="{{ route('importar-json') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="flex flex-col gap-4"
                >
                    @csrf
                    <input
                        type="file"
                        name="arquivo_json"
                        accept=".json,application/json"
                        required
                        class="border px-3 py-2 rounded"
                    >
                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                    >
                        Importar
                    </button>
                </form>
                @if(session('msg'))
                    <div class="mt-4 bg-green-100 text-green-800 px-4 py-2 rounded">{{ session('msg') }}</div>
                @endif
                @if(session('erro'))
                    <div class="mt-4 bg-red-100 text-red-800 px-4 py-2 rounded">{{ session('erro') }}</div>
                @endif
            </div>
        </div>
    </div>




    <div class="container mx-auto py-8">
        <h2 class="text-2xl font-bold mb-8">Mapa de Relacionamentos</h2>
        <div class="flex flex-col gap-8">
            @foreach($acs as $ac)
                <div class="flex items-start gap-4">
                    <!-- Card AC -->
                    <div class="bg-blue-100 border border-blue-700 rounded-lg min-w-[150px] px-6 py-8 flex items-center justify-center font-bold text-blue-900 shadow">
                        AC:<br>{{ $ac->nome }}
                    </div>

                    <!-- Linha dos ACN2 e ARs -->
                    <div class="flex flex-row items-start gap-6 flex-1">
                        @forelse($ac->acn2s as $acn2)
                            <div x-data="{ open: false }" class="flex items-start gap-2 relative">
                                <!-- Card ACN2 -->
                                <div class="bg-green-100 border border-green-500 rounded-lg min-w-[120px] px-4 py-6 flex items-center justify-center font-semibold text-green-900 shadow">
                                    ACN2:<br>{{ $acn2->nome }}
                                </div>
                                <!-- Botão + -->
                                <button @click="open=!open"
                                        class="ml-1 px-2 h-8 text-white bg-green-700 rounded hover:bg-green-900 focus:outline-none"
                                        :title="open ? 'Ocultar ARs' : 'Mostrar ARs'">
                                    <span x-show="!open">+</span>
                                    <span x-show="open">−</span>
                                </button>

                                <!-- ARs -->
                                <template x-if="open">
                                    <div class="flex gap-3 ml-1">
                                        @if($acn2->ars->count())
                                            @foreach($acn2->ars as $ar)
                                                <div class="bg-yellow-100 border border-yellow-500 rounded-lg min-w-[110px] px-3 py-4 flex flex-col items-center justify-center text-yellow-900 shadow text-sm">
                                                    <span class="font-bold">AR</span>
                                                    <span>{{ $ar->nome }}</span>
                                                    <span class="text-xs text-gray-500">Tipo: {{ $ar->tipo }}</span>
                                                    <span class="text-xs text-gray-500">Situação: {{ $ar->situacao }}</span>
                                                    <span class="text-xs text-gray-500">Open: {{ $ar->open ? 'Sim' : 'Não' }}</span>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-gray-500 ml-2 italic">Nenhuma AR</div>
                                        @endif
                                    </div>
                                </template>
                            </div>
                        @empty
                            <div class="text-gray-400 italic flex items-center">Nenhum ACN2</div>
                        @endforelse
                    </div>
                </div>
            @endforeach
            @if($acs->isEmpty())
                <div class="text-gray-500 italic text-center">Nenhuma AC cadastrada.</div>
            @endif
        </div>
    </div>

</x-app-layout>
