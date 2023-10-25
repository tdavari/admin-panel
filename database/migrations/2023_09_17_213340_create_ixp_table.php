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
        Schema::create('ixp', function (Blueprint $table) {
                $table->id();
                $table->string('switch');
                $table->string('vlan');
                $table->string('name');
                $table->string('vni');
                $table->string('intf');
                $table->string('desc');
                $table->string('learn_mac')->nullable();
                $table->integer('count');
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ixp');
    }
};
