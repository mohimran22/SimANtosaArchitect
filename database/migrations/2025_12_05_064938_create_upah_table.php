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
    Schema::create('labor_costs', function (Blueprint $table) {
        $table->id();
        $table->string('code')->nullable();               // Work Code
        $table->string('description');                    // Work Description
        $table->string('unit', 50);                       // Unit (e.g. OH)
        $table->decimal('base_unit_price', 15, 2);        // Base Unit Price (HSD)
        $table->text('notes')->nullable();                // Additional Notes
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('labor_costs');
}


};
