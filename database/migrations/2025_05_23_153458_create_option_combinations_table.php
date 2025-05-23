<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('option_combinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('if_option_id')->constrained('part_options')->onDelete('cascade');
            $table->foreignId('then_option_id')->constrained('part_options')->nullable(); // If null, then_part_id must be set
            $table->foreignId('then_part_id')->constrained('parts')->nullable(); // If null, then_option_id must be set
            $table->enum('rule_type', ['required', 'prohibited'])->default('required');
            $table->text('description')->nullable(); // Description of why this rule exists
            $table->boolean('active')->default(true);
            $table->timestamps();

            // Either then_option_id or then_part_id must be set, but not both
            $table->index(['if_option_id', 'then_option_id']);
            $table->index(['if_option_id', 'then_part_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('option_combinations');
    }
};
