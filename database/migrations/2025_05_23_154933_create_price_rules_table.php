<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('price_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('primary_option_id')->constrained('part_options')->onDelete('cascade');
            $table->foreignId('dependent_option_id')->constrained('part_options')->onDelete('cascade');
            $table->decimal('price_adjustment', 10, 2); // The price adjustment to apply
            $table->enum('adjustment_type', ['fixed', 'percentage'])->default('fixed');
            $table->text('description')->nullable(); // Description of why this price rule exists
            $table->boolean('active')->default(true);
            $table->timestamps();

            // Index for quick lookups
            $table->index(['primary_option_id', 'dependent_option_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('price_rules');
    }
};
