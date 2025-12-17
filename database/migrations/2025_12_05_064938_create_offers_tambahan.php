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
    Schema::table('offers', function (Blueprint $table) {
    $table->enum('status', ['draft', 'approved', 'rejected'])
      ->default('draft');
    $table->text('reject_reason')->nullable();
    $table->timestamp('approved_at')->nullable();
    $table->foreignUuid('approved_by')->nullable();
    });
}

public function down()
{
    //
}


};
