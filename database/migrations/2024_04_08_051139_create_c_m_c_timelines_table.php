<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCMCTimelinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_m_c_timelines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("po_requests_id")->nullable();
            $table->text("action")->nullable();
            $table->text("last_man")->nullable();
            $table->longText("edited_field")->nullable();
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
        Schema::dropIfExists('c_m_c_timelines');
    }
}
