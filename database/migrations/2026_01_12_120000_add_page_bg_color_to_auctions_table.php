<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('auctions', function (Blueprint $table) {
            if (!Schema::hasColumn('auctions', 'page_bg_color')) {
                $table->string('page_bg_color', 7)->nullable()->default('#ffffff')->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('auctions', function (Blueprint $table) {
            if (Schema::hasColumn('auctions', 'page_bg_color')) {
                $table->dropColumn('page_bg_color');
            }
        });
    }
};
