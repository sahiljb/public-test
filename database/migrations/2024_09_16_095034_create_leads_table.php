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
        // TODO: calling,called need to confirm with hardik
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assigned_userid')->nullable(); 
            $table->string('name');
            $table->string('phone');
            $table->string('city')->nullable();
            $table->enum('priority',['normal','moderate','hot'])->default('normal');
            $table->enum('status',['cold','calling','called'])->default('cold');
            $table->enum('proposal_status',['pending','interested','not_interested','phone_switch_off','remind_me'])->default('pending');
            $table->dateTime('reminder_date')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->foreign('assigned_userid')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
