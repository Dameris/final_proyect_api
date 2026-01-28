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
        Schema::create('joggers', function (Blueprint $table) {
            $table->id();
            $table->string("jogger_name");
            $table->string("jogger_composition");
            $table->string("jogger_fit");
            $table->integer("jogger_price");
            $table->string("jogger_img1");
            $table->string("jogger_img2");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('joggers');
    }
};
