<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExtendUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('users', function (Blueprint $table) {
            $table->string('surname')->nullable()->after('name');
            $table->dateTime('date_of_birth')->nullable();
            $table->string('license_id')->nullable();
            $table->double('renter_avg_rating')->nullable();
            $table->double('rentee_avg_rating')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('surname');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('license_id');
            $table->dropColumn('renter_avg_rating');
            $table->dropColumn('rentee_avg_rating');
        });
    }
}
