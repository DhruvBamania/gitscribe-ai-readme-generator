<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change column types to text to accommodate encrypted strings
        Schema::table('users', function (Blueprint $table) {
            $table->text('github_token')->nullable()->change();
            $table->text('github_refresh_token')->nullable()->change();
        });

        // Loop through all users and encrypt their plaintext tokens
        DB::table('users')->orderBy('id')->chunk(100, function ($users) {
            foreach ($users as $user) {
                // If it's already a long base64 string, it MIGHT be encrypted, but to be safe,
                // we'll try to decrypt it. If decrypt throws an exception, it's plaintext.
                $token = $user->github_token;
                $refreshToken = $user->github_refresh_token;

                if ($token) {
                    try {
                        Crypt::decryptString($token);
                    } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                        // It's plaintext, encrypt it
                        $token = Crypt::encryptString($token);
                    }
                }

                if ($refreshToken) {
                    try {
                        Crypt::decryptString($refreshToken);
                    } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                        // It's plaintext, encrypt it
                        $refreshToken = Crypt::encryptString($refreshToken);
                    }
                }

                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'github_token' => $token,
                        'github_refresh_token' => $refreshToken,
                    ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->orderBy('id')->chunk(100, function ($users) {
            foreach ($users as $user) {
                $token = $user->github_token;
                $refreshToken = $user->github_refresh_token;

                if ($token) {
                    try {
                        $token = Crypt::decryptString($token);
                    } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                        // Already plaintext
                    }
                }

                if ($refreshToken) {
                    try {
                        $refreshToken = Crypt::decryptString($refreshToken);
                    } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                        // Already plaintext
                    }
                }

                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'github_token' => $token,
                        'github_refresh_token' => $refreshToken,
                    ]);
            }
        });
    }
};
