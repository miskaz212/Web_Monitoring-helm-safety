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
       Schema::create('pekerjas', function (Blueprint $table) {
    $table->id();
    $table->string('device_id');
    $table->enum('status_helm', ['Helm_Terpakai', 'Helm_Tidak_Terpakai']);
    $table->enum('kondisi_pekerja', ['Tidak_Ada_Benturan', 'Benturan_Ringan', 'Benturan_Sedang', 'Benturan_Keras']);
    $table->enum('status_terbaring', ['Normal', 'Terbaring'])->default('Normal'); 
    $table->boolean('telegram_sent')->default(false);
    $table->decimal('latitude', 10, 6);
    $table->decimal('longitude', 10, 6);
    
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
        Schema::dropIfExists('pekerjas');
    }
};
