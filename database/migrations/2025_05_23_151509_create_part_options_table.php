<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('part_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., 'Full-suspension', 'Diamond', 'Step-through'
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2); // Base price of this option
            $table->integer('display_order')->default(0); // To control the order of options in the UI
            $table->boolean('in_stock')->default(true); // Whether this option is in stock
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('part_options');
    }
};
