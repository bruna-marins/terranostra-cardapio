<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmpresaPerfilController extends Controller
{
    public function show(){

        // Recuperar do banco de dados as informações do usuário logado
        $empresaId = Auth::user()->empresa_id;
        $empresa = Empresa::where('id', $empresaId)->get()->first();

        $colaboradores = Empresa::with('usuarios')->findOrFail($empresaId);

        // Carrega a view
        return view('empresa_profile.show', ['empresa' => $empresa, 'colaboradores' => $colaboradores]);
    }


    public function edit(){

        $empresaId = Auth::user()->empresa_id;
        $empresa = Empresa::where('id', $empresaId)->get()->first();

        return view('empresa_profile.edit', ['empresa' => $empresa]);
    }


    public function update(Request $request, Empresa $empresa){

        $empresaId = Auth::user()->empresa_id;
        $empresa = Empresa::where('id', $empresaId)->get()->first();

        $request->validate([
            'nome' => 'required|string|max:255',
            'razao_social' => 'required',
            'cnpj' => 'required|cnpj',
            'telefone' => 'required',
            'email' => 'required|email|unique:empresas',
            'cep' => 'required',
            'estado' => 'required',
            'cidade' => 'required',
            'bairro' => 'required',
            'rua' => 'required',
            'numero_endereco' => 'required',
            'complemento' => 'nullable'
        ],[
            // Mensagens de erro
            'nome.required' => 'O campo Nome é obrigatório.',
            'razao_social.required' => 'O campo Razão Social é obrigatório.',
            'cnpj.required' => 'O campo CNPJ é obrigatório.',
            'cnpj.cnpj' => 'Cnpj inválido.',
            'telefone.required' => 'O campo Telefone é obrigatório.',
            'email.required' => 'O campo E-mail é obrigatório.',
            'email.email' => 'Cnpj inválido.',
            'email.unique' => 'Esse E-mail já está sendo usado.',
            'cep.required' => 'O campo CEP é obrigatório.',
            'estado.required' => 'O campo Estado é obrigatório.',
            'cidade.required' => 'O campo Cidade é obrigatório.',
            'bairro.required' => 'O campo Bairro é obrigatório.',
            'rua.required' => 'O campo Rua é obrigatório.',
            'numero_endereco.required' => 'O campo Número é obrigatório.',
        ]);

        $empresa->update([
            'nome' => $request->nome,
            'razao_social' => $request->razao_social,
            'cnpj' => $request->cnpj,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'cep' => $request->cep,
            'estado' => $request->estado,
            'cidade' => $request->cidade,
            'bairro' => $request->bairro,
            'rua' => $request->rua,
            'numero_endereco' => $request->numero_endereco,
            'complemento' => $request->complemento
        ]);

        // Atualizar checkbox
        $empresa->situacao = $request->has('situacao');
        $empresa->save();

        return redirect()->route('empresa_profile.show', ['empresa' => $empresa])->with('success', 'Perfil editado com sucesso!');
    }


    public function editLogo(){

        $empresaId = Auth::user()->empresa_id;
        $empresa = Empresa::where('id', $empresaId)->get()->first();

        return view('empresa_profile.edit-logo', ['empresa' => $empresa]);
    }


    public function updateLogo(Request $request){

        $empresaId = Auth::user()->empresa_id;
        $empresa = Empresa::where('id', $empresaId)->get()->first();

        // Validar o upload
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($request->hasFile('logo')) {
            // Deletar a foto de perfil anterior, se existir
            if ($empresa->logo) {
                Storage::disk('public')->delete($empresa->logo);
            }

            // Armazenar a nova foto de perfil
            $file = $request->file('logo');
            $fileName = time() . '-' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $fileName, 'public');

            // Atualizar o caminho da foto de perfil no banco de dados
            $empresa->logo = $path;
            $empresa->save();
        }

        return redirect()->route('empresa_profile.show')->with('success', 'Logo atualizada com sucesso!');
    }


    public function colaboradores()
    {

        // Recuperar do banco de dados as informações do usuário logado
        $empresaId = Auth::user()->empresa_id;
        $empresa = Empresa::where('id', $empresaId)->get()->first();

        $userId = Auth::user()->id;

        // Recupera os usuários da empresa, ordenando o usuário logado como o primeiro
        $colaboradores = User::where('empresa_id', $empresaId)
            ->orderByRaw("id = $userId DESC")
            ->get();

        // Carrega a view
        return view('empresa_profile.colaboradores', ['empresa' => $empresa, 'colaboradores' => $colaboradores]);
    }
}
