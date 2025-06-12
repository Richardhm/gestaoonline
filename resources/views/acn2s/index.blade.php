<x-app-layout>
    <div class="container mx-auto py-8 mx-auto w-[80%]" x-data="crudACN2()">

        {{-- Mensagem de sucesso --}}
        @if(session('msg'))
            <div x-data="{show:true}" x-show="show"
                 x-init="setTimeout(()=>show=false, 2500)"
                 x-transition
                 class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded flex items-center justify-between" x-cloak>
                <span>{{ session('msg') }}</span>
                <button @click="show=false" class="ml-4 text-2xl font-bold text-green-800 leading-none">&times;</button>
            </div>
        @endif

        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-xl">Autoridades Certificadoras Nível 2 (AC N2)</h2>
            <button @click="openCreate()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                + Cadastrar AC N2
            </button>
        </div>

        <!-- Tabela -->
        <div class="overflow-x-auto bg-white shadow rounded">
            <table class="min-w-full text-left text-gray-700">
                <thead>
                <tr class="border-b">
                    <th class="py-3 px-4">#</th>
                    <th class="py-3 px-4">Nome</th>
                    <th class="py-3 px-4">Tipo</th>
                    <th class="py-3 px-4">Situação</th>
                    <th class="py-3 px-4">AC Principal</th>
                    <th class="py-3 px-4">Ações</th>
                </tr>
                </thead>
                <tbody>
                @foreach($acn2s as $acn2)
                    <tr class="border-b">
                        <td class="py-2 px-4">{{ $acn2->id }}</td>
                        <td class="py-2 px-4">{{ $acn2->nome }}</td>
                        <td class="py-2 px-4">{{ $acn2->tipo }}</td>
                        <td class="py-2 px-4">{{ $acn2->situacao }}</td>
                        <td class="py-2 px-4">{{ $acn2->ac->nome ?? '-' }}</td>
                        <td class="py-2 px-4 flex gap-2">
                            <button @click="openEdit({{ $acn2->id }}, `{{ addslashes($acn2->nome) }}`, `{{ addslashes($acn2->tipo) }}`, `{{ $acn2->situacao }}`, `{{ $acn2->ac_id }}`)"
                                    class="bg-yellow-400 text-white px-2 py-1 rounded hover:bg-yellow-500">
                                Editar
                            </button>
                            <form method="POST" action="{{ route('acn2s.destroy', $acn2) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600"
                                        onclick="return confirm('Tem certeza que deseja excluir este registro?')">
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if($acn2s->isEmpty())
                    <tr>
                        <td colspan="6" class="py-4 text-center text-gray-500">Nenhuma AC N2 cadastrada.</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        <!-- Modal de Cadastro -->
        <div x-show="showCreate" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 z-50">
            <form @click.away="showCreate=false" method="POST" action="{{ route('acn2s.store') }}"
                  class="bg-white p-8 rounded shadow w-80 flex flex-col">
                @csrf
                <h3 class="text-lg font-bold mb-4">Cadastrar AC N2</h3>
                <select name="ac_id" class="border px-3 py-2 rounded mb-3" required>
                    <option value="">Selecione a AC principal</option>
                    @foreach($acs as $ac)
                        <option value="{{ $ac->id }}">{{ $ac->nome }}</option>
                    @endforeach
                </select>
                <input type="text" name="nome" placeholder="Nome" class="border px-3 py-2 rounded mb-3" required>
                <input type="text" name="tipo" placeholder="Tipo" class="border px-3 py-2 rounded mb-3">
                <input type="text" name="situacao" placeholder="Situação" class="border px-3 py-2 rounded mb-3">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-2">Salvar</button>
                <button type="button" @click="showCreate=false" class="text-gray-500 underline mt-1">Cancelar</button>
            </form>
        </div>

        <!-- Modal de Edição -->
        <div x-show="showEdit" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 z-50">
            <form :action="'/acn2s/' + editId" method="POST" @click.away="showEdit=false"
                  class="bg-white p-8 rounded shadow w-80 flex flex-col">
                @csrf
                @method('PUT')
                <h3 class="text-lg font-bold mb-4">Editar AC N2</h3>
                <select name="ac_id" class="border px-3 py-2 rounded mb-3" required x-model="editAcId">
                    <option value="">Selecione a AC principal</option>
                    @foreach($acs as $ac)
                        <option value="{{ $ac->id }}">{{ $ac->nome }}</option>
                    @endforeach
                </select>
                <input type="text" name="nome" placeholder="Nome" class="border px-3 py-2 rounded mb-3" required x-model="editNome">
                <input type="text" name="tipo" placeholder="Tipo" class="border px-3 py-2 rounded mb-3" x-model="editTipo">
                <input type="text" name="situacao" placeholder="Situação" class="border px-3 py-2 rounded mb-3" x-model="editSituacao">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-2">Salvar</button>
                <button type="button" @click="showEdit=false" class="text-gray-500 underline mt-1">Cancelar</button>
            </form>
        </div>

    </div>


    <script>
        function crudACN2(){
            return {
                showCreate: false,
                showEdit: false,
                editId: null,
                editNome: '',
                editTipo: '',
                editSituacao: '',
                editAcId: '',
                openCreate(){
                    this.showCreate = true;
                },
                openEdit(id, nome, tipo, situacao, acId){
                    this.showEdit = true;
                    this.editId = id;
                    this.editNome = nome;
                    this.editTipo = tipo;
                    this.editSituacao = situacao;
                    this.editAcId = acId;
                }
            }
        }
    </script>
</x-app-layout>
