<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('build_process_items', function (Blueprint $table) {

            $table->string('floor_name')
                ->nullable()
                ->after('rab_item_id');

            $table->string('job_name')
                ->nullable()
                ->after('category_name');

            $table->text('description')
                ->nullable()
                ->after('job_name');

            $table->decimal('base_price', 15, 2)
                ->nullable()
                ->after('volume');

            $table->decimal('profit', 15, 2)
                ->nullable()
                ->after('total');

            $table->decimal('overhead', 15, 2)
                ->nullable()
                ->after('profit');

            $table->unsignedInteger('order_no')
                ->nullable()
                ->after('sumber');
        });


        Schema::table('build_plans', function (Blueprint $table) {

            $table->string('floor_name')
                ->nullable()
                ->after('rab_item_id');

            $table->string('job_name')
                ->nullable()
                ->after('category_name');

            $table->text('description')
                ->nullable()
                ->after('job_name');

            $table->decimal('base_price', 15, 2)
                ->nullable()
                ->after('volume');

            $table->decimal('profit', 15, 2)
                ->nullable()
                ->after('total');

            $table->decimal('overhead', 15, 2)
                ->nullable()
                ->after('profit');

            $table->unsignedInteger('order_no')
                ->nullable()
                ->after('job_category_id');
        });
    }

    public function down(): void
    {
        Schema::table('build_process_items', function (Blueprint $table) {

            $table->dropColumn([
                'floor_name',
                'job_name',
                'description',
                'base_price',
                'profit',
                'overhead',
                'order_no',
            ]);
        });


        Schema::table('build_plans', function (Blueprint $table) {

            $table->dropColumn([
                'floor_name',
                'job_name',
                'description',
                'base_price',
                'profit',
                'overhead',
                'order_no',
            ]);
        });
    }
};
