<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
      $table->uuid('persona_id');
      $table->foreign('persona_id')->references('id')->on('persona');
      $table->string('licencia_numero')->unique();
    });

    Schema::create('viaje', function (Blueprint $table) {
      $table->id('id');
      $table->date('fecha');
      $table->uuid('chofer_id');
      $table->foreign('chofer_id')->references('persona_id')->on('chofer');
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
      $table->uuid('persona_id');
      $table->foreign('persona_id')->references('id')->on('persona');
      $table->unsignedBigInteger('facultad_id');
      $table->foreign('facultad_id')->references('id')->on('facultad');
      $table->unsignedBigInteger('asignatura_id');
      $table->foreign('asignatura_id')->references('id')->on('asignatura');
      $table->unsignedBigInteger('destino_id');
      $table->foreign('destino_id')->references('id')->on('destino');
    });

    Schema::create('viaje_profesor', function (Blueprint $table) {
      $table->unsignedBigInteger('viaje_id');
      $table->foreign('viaje_id')->references('id')->on('viaje');
      $table->unsignedBigInteger('profesor_id');
      $table->foreign('profesor_id')->references('persona_id')->on('profesor');
    });

    Schema::create('facultad_asignatura', function (Blueprint $table) {
      $table->unsignedBigInteger('facultad_id');
      $table->foreign('facultad_id')->references('id')->on('facultad');
      $table->unsignedBigInteger('asignatura_id');
      $table->foreign('asignatura_id')->references('id')->on('asignatura');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('persona');
    Schema::dropIfExists('profesor');
    Schema::dropIfExists('viaje');
    Schema::dropIfExists('facultad');
    Schema::dropIfExists('chofer');
    Schema::dropIfExists('viaje_profesor');
    Schema::dropIfExists('destino');
    Schema::dropIfExists('asignatura');
    Schema::dropIfExists('facultad_asignatura');
  }
};
