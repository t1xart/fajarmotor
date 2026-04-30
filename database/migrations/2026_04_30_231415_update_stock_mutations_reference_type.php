<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('stock_mutations')->where('reference_type', 'App\Models\Sale')->update(['reference_type' => 'sale']);
        DB::table('stock_mutations')->where('reference_type', 'App\Models\PurchaseOrder')->update(['reference_type' => 'purchase_order']);
        DB::table('stock_mutations')->where('reference_type', 'App\Models\WorkOrder')->update(['reference_type' => 'work_order']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('stock_mutations')->where('reference_type', 'sale')->update(['reference_type' => 'App\Models\Sale']);
        DB::table('stock_mutations')->where('reference_type', 'purchase_order')->update(['reference_type' => 'App\Models\PurchaseOrder']);
        DB::table('stock_mutations')->where('reference_type', 'work_order')->update(['reference_type' => 'App\Models\WorkOrder']);
    }
};
