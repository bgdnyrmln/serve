<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cuisine')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->decimal('rating', 3, 2)->nullable();
            $table->boolean('open')->default(true);
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
