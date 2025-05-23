<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_type_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., 'Frame Type', 'Wheels', 'Chain'
            $table->text('description')->nullable();
            $table->integer('display_order')->default(0); // To control the order of parts in the UI
            $table->boolean('required')->default(false); // Whether this part must be selected
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('parts');
    }
};
