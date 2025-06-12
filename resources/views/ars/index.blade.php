<x-app-layout>
    <div class="container mx-auto py-8" x-data="crudAR()" x-cloak>

        {{-- Alerta de mensagem --}}
        @if(session('msg'))
            <div
                x-data="{show:true}"
                x-init="setTimeout(()=>show=false, 2500)"
                x-show="show"
                x-transition
                class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mx-auto relative flex items-center w-[80%] justify-between"
            >
                <span>{{ session('msg') }}</span>
                <button @click="show=false" class="ml-4 text-2xl font-bold text-green-800 leading-none">&times;</button>
            </div>
        @endif

        <div class="flex items-center justify-between mb-4 w-[80%] mx-auto">
            <h2 class="font-bold text-xl">Autoridades de Registro (AR)</h2>
            <button @click="openCreate()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                + Cadastrar AR
            </button>
        </div>

        <!-- Tabela -->
        <div class="overflow-x-auto bg-white shadow rounded w-[80%] mx-auto">
            <table class="min-w-full text-left text-gray-700">
                <thead>
                <tr class="border-b">
                    <th class="py-3 px-4">#</th>
                    <th class="py-3 px-4">Nome</th>
                    <th class="py-3 px-4">Tipo</th>
                    <th class="py-3 px-4">Situação</th>
                    <th class="py-3 px-4">Aberto?</th>
                    <th class="py-3 px-4">AC N2</th>
                    <th class="py-3 px-4">Ações</th>
                </tr>
                </thead>
                <tbody>
                @foreach($ars as $ar)
                    <tr class="border-b">
                        <td class="py-2 px-4">{{ $ar->id }}</td>
                        <td class="py-2 px-4">{{ $ar->nome }}</td>
                        <td class="py-2 px-4">{{ $ar->tipo }}</td>
                        <td class="py-2 px-4">{{ $ar->situacao }}</td>
                        <td class="py-2 px-4">
                            {{ $ar->open ? 'Sim' : 'Não' }}
                        </td>
                        <td class="py-2 px-4">
                            {{ $ar->acn2->nome ?? '-' }}
                        </td>
                        <td class="py-2 px-4 flex gap-2">
                            <button
                                @click="openEdit({{ $ar->id }}, '{{ addslashes($ar->nome) }}', '{{ addslashes($ar->tipo) }}', '{{ addslashes($ar->situacao) }}', '{{ $ar->open ? 1 : 0 }}', '{{ $ar->acn2_id }}')"
                                class="bg-yellow-400 text-white px-2 py-1 rounded hover:bg-yellow-500"
                            >
                                Editar
                            </button>
                            <form method="POST" action="{{ route('ars.destroy', $ar) }}">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600"
                                    onclick="return confirm('Tem certeza que deseja excluir este registro?')"
                                >
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if($ars->isEmpty())
                    <tr>
                        <td colspan="7" class="py-4 text-center text-gray-500">Nenhuma AR cadastrada.</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        <!-- Modal de Cadastro -->
        <div x-show="showCreate" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 z-50">
            <form @click.away="showCreate=false" method="POST" action="{{ route('ars.store') }}"
                  class="bg-white p-8 rounded shadow w-80 flex flex-col">
                @csrf
                <h3 class="text-lg font-bold mb-4">Cadastrar AR</h3>
                <select name="acn2_id" class="border px-3 py-2 rounded mb-3" required>
                    <option value="">Selecione a AC N2</option>
                    @foreach($acn2s as $acn2)
                        <option value="{{ $acn2->id }}">{{ $acn2->nome }}</option>
                    @endforeach
                </select>
                <input type="text" name="nome" placeholder="Nome" class="border px-3 py-2 rounded mb-3" required>
                <input type="text" name="tipo" placeholder="Tipo" class="border px-3 py-2 rounded mb-3">
                <input type="text" name="situacao" placeholder="Situação" class="border px-3 py-2 rounded mb-3">
                <label class="mb-2">
                    <input type="checkbox" name="open" value="1"
                           class="mr-2 align-middle">
                    Aberto?
                </label>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-2">Salvar</button>
                <button type="button" @click="showCreate=false" class="text-gray-500 underline mt-1">Cancelar</button>
            </form>
        </div>

        <!-- Modal de Edição -->
        <div x-show="showEdit" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 z-50">
            <form :action="'/ars/' + editId"
                  method="POST" @click.away="showEdit=false"
                  class="bg-white p-8 rounded shadow w-80 flex flex-col">
                @csrf
                @method('PUT')
                <h3 class="text-lg font-bold mb-4">Editar AR</h3>
                <select name="acn2_id" class="border px-3 py-2 rounded mb-3" required x-model="editAcn2Id">
                    <option value="">Selecione a AC N2</option>
                    @foreach($acn2s as $acn2)
                        <option value="{{ $acn2->id }}">{{ $acn2->nome }}</option>
                    @endforeach
                </select>
                <input type="text" name="nome" placeholder="Nome" class="border px-3 py-2 rounded mb-3" x-model="editNome" required>
                <input type="text" name="tipo" placeholder="Tipo" class="border px-3 py-2 rounded mb-3" x-model="editTipo">
                <input type="text" name="situacao" placeholder="Situação" class="border px-3 py-2 rounded mb-3" x-model="editSituacao">

                <label class="mb-2 inline-flex items-center">
                    <input type="hidden" name="open" value="0">
                    <input type="checkbox"
                           name="open"
                           value="1"
                           class="mr-2 align-middle"
                           x-model="editOpen"
                           :checked="editOpen == 1"
                    >
                    Aberto?
                </label>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-2">Salvar</button>
                <button type="button" @click="showEdit=false" class="text-gray-500 underline mt-1">Cancelar</button>
            </form>
        </div>

    </div>

    <script>
        function crudAR(){
            return {
                showCreate: false,
                showEdit: false,
                editId: null,
                editNome: '',
                editTipo: '',
                editSituacao: '',
                editOpen: 0,
                editAcn2Id: '',
                openCreate(){
                    this.showCreate = true;
                },
                openEdit(id, nome, tipo, situacao, open, acn2_id){
                    this.showEdit = true;
                    this.editId = id;
                    this.editNome = nome;
                    this.editTipo = tipo;
                    this.editSituacao = situacao;
                    this.editOpen = (open == "1" || open == 1) ? 1 : 0;
                    this.editAcn2Id = acn2_id;
                }
            }
        }
    </script>
</x-app-layout>
