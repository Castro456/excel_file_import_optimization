<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
            $table->dropColumn('order_date');
            $table->dropColumn('order_id');
            $table->dropColumn('ship_date');
            $table->dropColumn('units_sold');
            $table->dropColumn('unit_price');
            $table->dropColumn('unit_cost');
            $table->dropColumn('total_revenue');
            $table->dropColumn('total_cost');
            $table->dropColumn('total_profit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->timestamps();
            $table->date('order_date');
            $table->string('order_id');
            $table->date('ship_date');
            $table->integer('units_sold');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('unit_cost', 10, 2);
            $table->decimal('total_revenue', 10, 2);
            $table->decimal('total_cost', 10, 2);
            $table->decimal('total_profit', 10, 2);
        });
    }
};
