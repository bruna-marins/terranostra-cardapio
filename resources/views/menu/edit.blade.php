@extends('layouts.admin')

@section('content')

    <h2>Editar Item</h2>

    <x-alert/>

    <a href="{{ route('menu.index') }}">
        <button type="button">Lista de produtos</button>
    </a>


    <form action="{{ route('menu.update', ['menu' => $menu->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Nome: </label>
        <input type="text" name="nome" id="nome" placeholder="Nome do Produto" value="{{ $menu->nome }}">

        <label>Descrição: </label>
        <textarea name="descricao" id="descricao" cols="30" rows="4">{{ $menu->descricao }}</textarea>

        <label>Preço: </label>
        <input type="text" name="preco" id="preco" placeholder="Valor do Produto" value="{{ $menu->preco }}">

        <label for="categoria_id">Categoria:</label>
        <select id="categoria_id" name="categoria_id">
            <option value="">Selecione uma Categoria</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                    {{ $categoria->categoria }}
                </option>
            @endforeach
        </select>

        <label for="product_file_name">Selecione o arquive de imagem:</label>
        <input type="file" name="product_file_name" id="product_file_name">
        <img src="{{ asset("storage/$menu->product_file_name}") }}" alt="[imagem]">

        <button type="submit">Editar</button>

    </form>
        
@endsection