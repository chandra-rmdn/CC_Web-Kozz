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
        Schema::table('chat_message', function (Blueprint $table) {
            $table->foreign(['chat_room_id'])->references(['id'])->on('chat_room')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['sender_id'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_message', function (Blueprint $table) {
            $table->dropForeign('chat_message_chat_room_id_foreign');
            $table->dropForeign('chat_message_sender_id_foreign');
        });
    }
};
