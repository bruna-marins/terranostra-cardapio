<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoriaRequest;
use App\Models\Categoria;
use App\Models\Menu;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Facades\Facade;

class CategoriaController extends Controller
{
    public function index(){

        $empresaId = Auth::user()->empresa_id;
        $categorias = Categoria::where('empresa_id', $empresaId)->get();

        return view('categoria.index', compact('categorias'));
    }


    public function show($categoria){

        $categorias_names = Categoria::with('menus')->findOrFail($categoria);


        return view('categoria.show', compact('categorias_names'), ['categoria' => $categoria]);
    }


    public function create(){

        return view('categoria.create');
    }


    public function store(CategoriaRequest $request){
    
        //Validar formulário
        $request->validated();

        $empresa_id = Auth::user()->empresa_id;


        DB::beginTransaction();
        
        try{
            Categoria::create([
                'categoria' => $request->categoria,
                'empresa_id'=>$empresa_id,
            ]);

            //Operação concluída com êxito
            DB::commit();

            // Redirecionar o usuário
            return redirect()->route('categoria.create')->with('success', 'Categoria cadastrada com sucesso!');

        } catch(Exception $e){
            //Operação não concluída com êxito
            DB::rollBack();
            // Redirecionar o usuário
            return back()->withInput()->with('error', 'Categoria não foi cadastrada!');
        }
    }


    public function edit(Categoria $categoria){

        // Carrega a View
        return view('categoria.edit', ['categoria' => $categoria]);
    }


    public function update(CategoriaRequest $request, Categoria $categoria){

        //Validar formulário
        $request->validated();

        DB::beginTransaction();

        try{
            //Editar as informações
            $categoria->update([
                'categoria' => $request->categoria
            ]);

            DB::commit();
        
            return redirect()->route('categoria.show', ['categoria' => $categoria->id])->with('success', 'Categoria editada com sucesso!');
        } catch(Exception $e){
            //Operação não concluída com êxito
            DB::rollBack();
            // Redirecionar o usuário
            return back()->withInput()->with('error', 'Categoria não foi editada!');
        }
    }


    public function destroy(Categoria $categoria){

        try{
        //Excluir registro
        $categoria->delete();

        //Redirecionar o usuário
        return redirect()->route('categoria.index')->with('success', 'Categoria excluida com sucesso!');

        } catch(Exception $e){
            return redirect()->route('categoria.index')->with('error', ' A categoria não pode ser excluida!');
        }
    }
}
