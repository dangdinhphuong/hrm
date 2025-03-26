<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('monthly_timesheet_summary', function (Blueprint $table) {
            $table->float('remaining_leave_days')->default(0)->after('total_late_early_minutes');
        });
    }

    public function down()
    {
        Schema::table('monthly_timesheet_summary', function (Blueprint $table) {
            $table->dropColumn('remaining_leave_days');
        });
    }
};
