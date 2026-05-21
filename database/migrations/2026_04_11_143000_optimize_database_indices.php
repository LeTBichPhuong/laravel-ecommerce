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
        Schema::table('orders', function (Blueprint $table) {
            // Index status for fast dashboard summary queries
            if (Schema::hasColumn('orders', 'status')) {
                $table->index('status');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            // Index price and gender for common storefront filters
            if (Schema::hasColumn('products', 'price')) {
                $table->index('price');
            }
            if (Schema::hasColumn('products', 'gender')) {
                $table->index('gender');
            }
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['price']);
            $table->dropIndex(['gender']);
        });
    }
};
