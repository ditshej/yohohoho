<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('card_id')->unique();
            $table->string('pack_id')->nullable();
            $table->string('name');
            $table->string('rarity');
            $table->string('category');
            $table->json('colors');
            $table->integer('cost')->nullable();
            $table->json('attributes')->nullable();
            $table->integer('power')->nullable();
            $table->integer('counter')->nullable();
            $table->json('types')->nullable();
            $table->text('effect')->nullable();
            $table->text('trigger')->nullable();
            $table->string('img_url')->nullable();
            $table->boolean('is_manually_created')->default(false);
            $table->timestamps();
        });
    }
};
