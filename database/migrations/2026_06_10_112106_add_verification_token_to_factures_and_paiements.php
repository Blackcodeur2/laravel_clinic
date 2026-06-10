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
        Schema::table('factures', function (Blueprint $table) {
            $table->string('verification_token', 64)->nullable()->unique()->after('statut');
        });

        Schema::table('paiements', function (Blueprint $table) {
            $table->string('verification_token', 64)->nullable()->unique()->after('date_paiement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropColumn('verification_token');
        });

        Schema::table('paiements', function (Blueprint $table) {
            $table->dropColumn('verification_token');
        });
    }
};
