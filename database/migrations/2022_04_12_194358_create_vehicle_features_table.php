<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_features', function (Blueprint $table) {
            $table->foreignId('vehicle_id')->constrained('vehicles', 'vehicle_id');
            $table->string('transmission');
            $table->string('type');
            $table->boolean('heated_seats');
            $table->boolean('ac');
            $table->boolean('aux');
            $table->string('colour');
            $table->string('drivetrain');
            $table->float('horsepower');
            $table->float('fuel_capacity');
            $table->string('fuel_type');
            $table->string('tyres');
            $table->float('avg_consumption');
            $table->boolean('wheelchair');
            $table->boolean('child_seat');
            $table->boolean('backup_camera');
            $table->string('parking_sensors');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_features');
    }
}
