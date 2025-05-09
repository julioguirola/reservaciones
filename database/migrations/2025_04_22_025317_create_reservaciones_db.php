<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('persona', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->string('nombre');
      $table->string('carnet_identidad')->unique();
    });

    Schema::create('chofer', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->uuid('persona_id');
      $table->softDeletes();
      $table->foreign('persona_id')->references('id')->on('persona')->cascadeOnDelete();
      $table->string('licencia_numero')->unique();
    });

    Schema::create('viaje', function (Blueprint $table) {
      $table->id('id');
      $table->date('fecha');
      $table->uuid('chofer_id');
      $table->foreign('chofer_id')->references('id')->on('chofer')->cascadeOnUpdate();
    });

    Schema::create('facultad', function (Blueprint $table) {
      $table->id('id');
      $table->string('nombre');
    });

    Schema::create('asignatura', function (Blueprint $table) {
      $table->id('id');
      $table->string('nombre');
    });

    Schema::create('destino', function (Blueprint $table) {
      $table->id('id');
      $table->string('nombre');
      $table->double('precio');
    });

    Schema::create('profesor', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->uuid('persona_id');
      $table->softDeletes();
      $table->foreign('persona_id')->references('id')->on('persona')->cascadeOnDelete();
      $table->unsignedBigInteger('facultad_id');
      $table->foreign('facultad_id')->references('id')->on('facultad');
      $table->unsignedBigInteger('asignatura_id');
      $table->foreign('asignatura_id')->references('id')->on('asignatura');
      $table->unsignedBigInteger('destino_id');
      $table->foreign('destino_id')->references('id')->on('destino');
    });

    Schema::create('profesor_viaje', function (Blueprint $table) {
      $table->unsignedBigInteger('profesor_id');
      $table->foreign('profesor_id')->references('id')->on('profesor')->cascadeOnDelete();
      $table->unsignedBigInteger('viaje_id');
      $table->foreign('viaje_id')->references('id')->on('viaje')->cascadeOnDelete();
    });

    Schema::create('asignatura_facultad', function (Blueprint $table) {
      $table->unsignedBigInteger('asignatura_id');
      $table->foreign('asignatura_id')->references('id')->on('asignatura');
      $table->unsignedBigInteger('facultad_id');
      $table->foreign('facultad_id')->references('id')->on('facultad');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    DB::statement('PRAGMA foreign_keys=OFF;');
    Schema::dropIfExists('persona');
    Schema::dropIfExists('profesor');
    Schema::dropIfExists('viaje');
    Schema::dropIfExists('facultad');
    Schema::dropIfExists('chofer');
    Schema::dropIfExists('profesor_viaje');
    Schema::dropIfExists('destino');
    Schema::dropIfExists('asignatura');
    Schema::dropIfExists('asignatura_facultad');
    DB::statement('PRAGMA foreign_keys=ON;');
  }
};
