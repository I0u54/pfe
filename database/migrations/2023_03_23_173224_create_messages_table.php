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
        Schema::create('messages', function (Blueprint $table) {
            $table->id('idMessage');
            $table->unsignedBigInteger('idSender');
            $table->foreign('idSender')->references('idUser')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('idReceiver');
            $table->foreign('idReceiver')->references('idUser')->on('users')->onDelete('cascade');
            $table->longText('message_text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
