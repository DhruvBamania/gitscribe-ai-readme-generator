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
        Schema::create('webhooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('repo_full_name'); // e.g. "owner/repo"
            $table->string('github_webhook_id')->nullable();
            $table->string('secret');
            $table->timestamps();
            
            // A user can only have one webhook per repo in our system to avoid duplicates
            $table->unique(['user_id', 'repo_full_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhooks');
    }
};
