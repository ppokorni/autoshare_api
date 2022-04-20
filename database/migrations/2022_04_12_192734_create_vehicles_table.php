<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id('vehicle_id');
            $table->foreignId('owner_id')->constrained('users');
            $table->string('brand');
            $table->string('model');
            $table->smallInteger('year');
            $table->text('description')->nullable();
            $table->string('licence_plate');
            $table->date('registered_until');
            $table->float('rent_cost')->nullable();
            $table->float('daily_distance_limit')->nullable();
            $table->float('cost_per_kilometer')->nullable();
            $table->float('rating_avg')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('vehicles');
    }
}
