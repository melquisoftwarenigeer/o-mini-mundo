<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->enum('status', ['Ativo', 'Inativo'])->default('Ativo');
            $table->decimal('budget', 15, 2)->nullable();
            $table->foreignId('user_id')
                ->nullable() // Use nullable se ainda não sabe os valores
                ->constrained()
                ->onDelete('cascade')
                ->comment('Usuário responsável pelo projeto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
};
