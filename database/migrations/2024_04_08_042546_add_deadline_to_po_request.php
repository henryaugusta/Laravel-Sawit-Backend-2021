<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeadlineToPoRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('po_requests', function (Blueprint $table) {
            $table->text('attachment')->nullable();
            $table->dateTime('deadline')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('po_requests', function (Blueprint $table) {
            $table->dropColumn("deadline");
            $table->dropColumn("attachment");
        });
    }
}
