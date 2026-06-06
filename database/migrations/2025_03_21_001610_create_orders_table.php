<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create("orders", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->onDelete("cascade");
            $table->decimal("total_price", 8, 2);
            $table->string("status")->default("Processed");
            $table->string("shipping_name")->nullable();
            $table->string("shipping_address")->nullable();
            $table->string("shipping_city")->nullable();
            $table->string("shipping_zip", 10)->nullable();
            $table->string("shipping_phone", 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("orders");
    }
};
