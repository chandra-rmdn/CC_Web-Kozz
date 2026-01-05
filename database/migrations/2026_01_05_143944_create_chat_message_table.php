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
        Schema::create('chat_message', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('chat_room_id')->index('chat_message_chat_room_id_foreign');
            $table->unsignedBigInteger('sender_id')->index('chat_message_sender_id_foreign');
            $table->text('pesan')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_message');
    }
};
