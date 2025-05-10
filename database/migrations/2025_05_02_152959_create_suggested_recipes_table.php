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
        Schema::create('suggested_recipes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('instruction')->nullable();
            $table->float('protein')->default(0);
            $table->float('fat')->default(0);
            $table->float('carbohydrate')->default(0);
            $table->integer('calorie')->default(0);
            // 割合
            $table->float('protein_ratio')->default(0);
            $table->float('fat_ratio')->default(0);
            $table->float('carbohydrate_ratio')->default(0);

            $table->timestamps();
       });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suggested_recipes');
    }
};
