<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('empresas', function(Blueprint $table) {
            $table->string('razao_social');
            $table->string('cnpj');
            $table->string('situacao')->default('ativo');
            $table->string('telefone');
            $table->string('responsavel');
            $table->string('email');
            $table->string('cep');
            $table->string('numero_endereco');
            $table->string('rua');
            $table->string('bairro');
            $table->string('cidade');
            $table->string('estado');
            $table->text('complemento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function(Blueprint $table) {
            $table->dropColumn('razao_social');
            $table->dropColumn('cnpj');
            $table->dropColumn('situacao');
            $table->dropColumn('telefone');
            $table->dropColumn('responsavel');
            $table->dropColumn('email');
            $table->dropColumn('cep');
            $table->dropColumn('numero_endereco');
            $table->dropColumn('rua');
            $table->dropColumn('bairro');
            $table->dropColumn('cidade');
            $table->dropColumn('estado');
            $table->dropColumn('complemento');
        });
    }
};
