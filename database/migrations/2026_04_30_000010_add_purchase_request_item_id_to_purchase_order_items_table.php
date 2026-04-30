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
        if (!Schema::hasColumn('purchase_order_items', 'purchase_request_item_id')) {
            Schema::table('purchase_order_items', function (Blueprint $table) {
                $table->foreignId('purchase_request_item_id')
                    ->nullable()
                    ->after('purchase_order_id')
                    ->constrained('purchase_request_items')
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('purchase_order_items', 'purchase_request_item_id')) {
            Schema::table('purchase_order_items', function (Blueprint $table) {
                $table->dropConstrainedForeignId('purchase_request_item_id');
            });
        }
    }
};
