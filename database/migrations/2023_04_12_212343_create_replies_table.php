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
        Schema::create('replies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idReplier');
            $table->foreign('idReplier')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('idComment');
            $table->foreign('idComment')->references('idComment')->on('comments')->onDelete('cascade');
            $table->longText('replyBody');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('replies');
    }
};
