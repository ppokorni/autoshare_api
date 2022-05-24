<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDoorsAndSeatsToVehicleFeatures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_features', function (Blueprint $table) {
            $table->smallInteger('door_count')->nullable();
            $table->smallInteger('seat_count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_features', function (Blueprint $table) {
            $table->dropColumn('door_count');
            $table->dropColumn('seat_count');
        });
    }
}
