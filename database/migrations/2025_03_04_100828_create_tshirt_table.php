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
        Schema::create('tshirts', function (Blueprint $table) {
            $table->id();
            $table->string("tshirt_name");
            $table->string("tshirt_composition");
            $table->string("tshirt_fit");
            $table->integer("tshirt_price");
            // $table->string("tshirt_img1");
            // $table->string("tshirt_img2");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tshirts');
    }
};
