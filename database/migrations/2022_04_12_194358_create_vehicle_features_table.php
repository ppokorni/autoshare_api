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
            $table->string('transmission')->nullable();
            $table->string('type')->nullable();
            $table->boolean('heated_seats')->nullable();
            $table->boolean('ac')->nullable();
            $table->boolean('aux')->nullable();
            $table->string('colour')->nullable();
            $table->string('drivetrain')->nullable();
            $table->float('horsepower')->nullable();
            $table->float('fuel_capacity')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('tyres')->nullable();
            $table->float('avg_consumption')->nullable();
            $table->boolean('wheelchair')->nullable();
            $table->boolean('child_seat')->nullable();
            $table->boolean('backup_camera')->nullable();
            $table->string('parking_sensors')->nullable();
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
