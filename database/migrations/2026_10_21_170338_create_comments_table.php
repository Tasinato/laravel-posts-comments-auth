<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('post_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('content');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};

// A exclusão de um post remove também seus comentários.
// Decisão técnica: Adotado exclusão em cascata para manter a integridade referencial e simplificar o projeto.
// Isso não significa que qualquer usuário poderá excluir dados; as permissões de exclusão serão implementadas nas Policies.