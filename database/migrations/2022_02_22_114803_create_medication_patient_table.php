<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicationPatientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medication_patient', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medication_id');
            $table->foreignId('patient_id');
            $table->string('dosage');
            $table->timestamp('startDate')->nullable();
            $table->timestamp('completionDate')->nullable();
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
        Schema::dropIfExists('medication_patient');
    }
}
