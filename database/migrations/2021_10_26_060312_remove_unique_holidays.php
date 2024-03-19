<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueHolidays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
     {
       Schema::table('holidays', function (Blueprint $table) {
         $table->dropUnique('holidays_date_unique');
       });
      }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
