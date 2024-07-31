<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnForGroupsSort extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('groups', function (Blueprint $table) {
            if (!Schema::hasColumn('groups', 'trade_volume')) {
                $table->decimal('trade_volume', 20, 2)->default(0.00)->comment('交易量');
            }
            if (!Schema::hasColumn('groups', 'recent_dispute')) {
                $table->unsignedInteger('recent_dispute')->default(0)->comment('最近的纠纷时间');
            }
            if (!Schema::hasColumn('groups', 'opening_at')) {
                $table->dateTime('opening_at')->nullable()->comment('开群日期');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn('trade_volume');
            $table->dropColumn('recent_dispute');
            $table->dropColumn('opening_at');
        });
    }
}
