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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')
                ->constrained('projects')
                ->onDelete('cascade')
                ->comment('Relacionamento com projetos');

            $table->string('description');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->foreignId('predecessor_id')
                ->nullable()
                ->constrained('tasks')
                ->nullOnDelete()
                ->comment('Tarefa predecessora');

            $table->enum('status', ['Pendente', 'Concluida', 'Em Andamento'])
                ->default('Pendente');

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
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
