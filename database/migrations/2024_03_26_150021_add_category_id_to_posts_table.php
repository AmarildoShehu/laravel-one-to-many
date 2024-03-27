<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            //definizione colonna 
            //$table->unsignedBigInteger('category_id')->nullable()->afer('id');
            
            //definizione chiave esterna
            //$table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();

            //$table->foreignId('category_id')->afer('id')->nullable()->constrained()->nullOnDelete();
            
            $table->foreignId(Category::class)->after('id')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Rimuovo la relazione
            $table->dropForeign(Category::class);
            
            // Rimuovi la colonna
            $table->dropColumn('category_id');
        });
    }
};
