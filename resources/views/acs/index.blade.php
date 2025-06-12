<x-app-layout>

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




        <div class="container mx-auto py-8 w-[80%]" x-data="crudAC()">

            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-xl">Autoridades Certificadoras (AC)</h2>
                <button @click="openCreate()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                    + Cadastrar AC
                </button>
            </div>

            <!-- Tabela -->
            <div class="overflow-x-auto bg-white shadow rounded">
                <table class="min-w-full text-left text-gray-700">
                    <thead>
                    <tr class="border-b">
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Nome</th>
                        <th class="py-3 px-4">Telefone</th>
                        <th class="py-3 px-4">Situação</th>
                        <th class="py-3 px-4">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($acs as $ac)
                        <tr class="border-b">
                            <td class="py-2 px-4">{{ $ac->id }}</td>
                            <td class="py-2 px-4">{{ $ac->nome }}</td>
                            <td class="py-2 px-4">{{ $ac->telefone }}</td>
                            <td class="py-2 px-4">{{ $ac->situacao }}</td>
                            <td class="py-2 px-4 flex gap-2">
                                <button @click="openEdit({{ $ac->id }}, '{{ addslashes($ac->nome) }}', '{{ addslashes($ac->telefone) }}', '{{ $ac->situacao }}')"
                                        class="bg-yellow-400 text-white px-2 py-1 rounded hover:bg-yellow-500">
                                    Editar
                                </button>
                                <form method="POST" action="{{ route('acs.destroy', $ac) }}">
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
                    @if($acs->isEmpty())
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-500">Nenhuma AC cadastrada.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            <!-- Modal de Cadastro -->
            <div x-show="showCreate" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 z-50">
                <form @click.away="showCreate=false" method="POST" action="{{ route('acs.store') }}"
                      class="bg-white p-8 rounded shadow w-80 flex flex-col">
                    @csrf
                    <h3 class="text-lg font-bold mb-4">Cadastrar AC</h3>
                    <input type="text" name="nome" placeholder="Nome" class="border px-3 py-2 rounded mb-3" required>
                    <input type="text" name="telefone" placeholder="Telefone" id="telefoneCreate" class="border px-3 py-2 rounded mb-3" maxlength="15"/>
                    <input type="text" name="situacao" placeholder="Situação" class="border px-3 py-2 rounded mb-3">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-2">Salvar</button>
                    <button type="button" @click="showCreate=false" class="text-gray-500 underline mt-1">Cancelar</button>
                </form>
            </div>

            <!-- Modal de Edição -->
            <div x-show="showEdit" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 z-50">
                <form :action="'/acs/' + editId" method="POST" @click.away="showEdit=false"
                      class="bg-white p-8 rounded shadow w-80 flex flex-col">
                    @csrf
                    @method('PUT')
                    <h3 class="text-lg font-bold mb-4">Editar AC</h3>
                    <input type="text" name="nome" placeholder="Nome" class="border px-3 py-2 rounded mb-3" x-model="editNome" required>
                    <input type="text" name="telefone" placeholder="Telefone" class="border px-3 py-2 rounded mb-3" x-model="editTelefone">
                    <input type="text" name="situacao" placeholder="Situação" class="border px-3 py-2 rounded mb-3" x-model="editSituacao">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-2">Salvar</button>
                    <button type="button" @click="showEdit=false" class="text-gray-500 underline mt-1">Cancelar</button>
                </form>
            </div>

        </div>

        <script>
            function crudAC(){
                return {
                    showCreate: false,
                    showEdit: false,
                    editId: null,
                    editNome: '',
                    editTelefone: '',
                    editSituacao: '',
                    openCreate(){
                        this.showCreate = true;
                    },
                    openEdit(id, nome, telefone, situacao){
                        this.showEdit = true;
                        this.editId = id;
                        this.editNome = nome;
                        this.editTelefone = telefone;
                        this.editSituacao = situacao;
                    }
                }
            }
        </script>

</x-app-layout>
