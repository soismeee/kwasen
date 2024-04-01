<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenerimaanBansosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penerimaan_bansos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('penduduk_nik')->index();
            $table->string('penghasilan');
            $table->string('status');
            $table->string('polri_asn');
            $table->string('pbl');
            $table->string('dtks');
            $table->string('validasi')->nullable();
            $table->date('tanggal');
            $table->timestamps();

            $table->foreign('penduduk_nik')->references('nik')->on('penduduks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penerimaan_bansos');
    }
}
