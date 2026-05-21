<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thêm cột stock (số lượng tồn kho) vào bảng product_sizes.
     */
    public function up(): void
    {
        Schema::table('product_sizes', function (Blueprint $table) {
            // Số lượng tồn kho của từng size, mặc định 0, thêm sau cột 'size'
            $table->unsignedInteger('stock')->default(0)->after('size');
        });
    }

    /**
     * Xóa cột stock nếu rollback.
     */
    public function down(): void
    {
        Schema::table('product_sizes', function (Blueprint $table) {
            $table->dropColumn('stock');
        });
    }
};
