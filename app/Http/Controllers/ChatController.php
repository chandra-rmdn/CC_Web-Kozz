<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // GET: Daftar chat rooms untuk user yang login
    public function getRooms()
    {
        $user = Auth::user();

        if ($user->role === 'penyewa') {
            // Penyewa: ambil rooms di mana user_id = ID penyewa
            $rooms = ChatRoom::where('user_id', $user->id)
                ->with([
                    'kos',
                    'kos.owner',
                    'messages' => function ($query) {
                        $query->latest()->limit(1);
                    }
                ])
                ->orderBy('updated_at', 'desc')
                ->get();
        } else {
            // Pemilik: ambil rooms di mana kos dimiliki oleh pemilik ini
            $rooms = ChatRoom::whereHas('kos', function ($query) use ($user) {
                $query->where('user_id', $user->id); // user_id di kos adalah pemilik_id
            })
                ->with([
                    'user',
                    'messages' => function ($query) {
                        $query->latest()->limit(1);
                    }
                ])
                ->orderBy('updated_at', 'desc')
                ->get();
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role
            ],
            'rooms' => $rooms
        ]);
    }

    // GET: Detail chat room dengan semua pesan
    public function getRoom($roomId)
    {
        $user = Auth::user();
        $room = ChatRoom::with([
            'kos',
            'kos.owner',
            'user',
            'messages' => function ($query) {
                $query->orderBy('created_at', 'asc');
            }
        ])
            ->findOrFail($roomId);

        // Cek authorization
        if ($user->role === 'penyewa') {
            // Penyewa hanya bisa melihat chat room miliknya
            if ($room->user_id != $user->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        } else {
            // Pemilik hanya bisa melihat chat room dari kos miliknya
            if ($room->kos->user_id != $user->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        }

        return response()->json([
            'success' => true,
            'room' => $room,
            'current_user_id' => $user->id
        ]);
    }

    // POST: Kirim pesan
    // Gunakan versi pertama, TAPI update bagian create message
    public function sendMessage(Request $request, $roomId)
    {
        $request->validate([
            'pesan' => 'required|string|max:1000'
        ]);

        $user = Auth::user();
        $room = ChatRoom::with('kos')->findOrFail($roomId);

        // Authorization check
        $allowedUsers = [$room->user_id]; // Penyewa
        if ($room->kos) {
            $allowedUsers[] = $room->kos->user_id; // Pemilik kos
        }

        if (!in_array($user->id, $allowedUsers)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // BUAT MANUAL UNTUK PASTIKAN created_at TERKIRIM
        $message = new ChatMessage();
        $message->chat_room_id = $roomId;
        $message->sender_id = $user->id;
        $message->pesan = $request->pesan;
        $message->created_at = now(); // SET MANUAL
        $message->save();

        // Update timestamp room untuk urutan daftar chat
        $room->touch();

        // RESPONSE DENGAN SEMUA DATA TERMASUK created_at
        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'chat_room_id' => $message->chat_room_id,
                'sender_id' => $message->sender_id,
                'pesan' => $message->pesan,
                'created_at' => $message->created_at->toDateTimeString() // FORMAT STRING
            ]
        ]);
    }

    // POST: Buat chat room baru
    public function createRoom(Request $request)
    {
        $request->validate([
            'kos_id' => 'required|exists:kos,id'
        ]);

        $user = Auth::user();

        // Hanya penyewa yang bisa membuat room baru
        if ($user->role !== 'penyewa') {
            return response()->json([
                'success' => false,
                'error' => 'Hanya penyewa yang bisa memulai chat'
            ], 403);
        }

        // Cek apakah kos aktif
        $kos = Kos::where('id', $request->kos_id)
            ->where('status', 'active')
            ->first();

        if (!$kos) {
            return response()->json([
                'success' => false,
                'error' => 'Kos tidak tersedia'
            ], 404);
        }

        // Cek apakah room sudah ada
        $existingRoom = ChatRoom::where('kos_id', $request->kos_id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingRoom) {
            return response()->json([
                'success' => true,
                'room' => $existingRoom,
                'already_exists' => true,
                'message' => 'Chat room sudah ada'
            ]);
        }

        // Buat room baru
        $room = ChatRoom::create([
            'kos_id' => $request->kos_id,
            'user_id' => $user->id
        ]);

        // Load relasi untuk response
        $room->load(['kos', 'kos.owner']);

        return response()->json([
            'success' => true,
            'room' => $room,
            'already_exists' => false,
            'message' => 'Chat room berhasil dibuat'
        ]);
    }

    public function getOrCreateRoom(Request $request)
    {
        $request->validate([
            'kos_id' => 'required|exists:kos,id'
        ]);

        $user = Auth::user();

        // Hanya penyewa yang bisa memulai chat
        if ($user->role !== 'penyewa') {
            return response()->json([
                'success' => false,
                'error' => 'Hanya penyewa yang bisa memulai chat'
            ], 403);
        }

        // Cek apakah room sudah ada
        $room = ChatRoom::where('kos_id', $request->kos_id)
            ->where('user_id', $user->id)
            ->with([
                'kos',
                'kos.owner',
                'messages' => function ($query) {
                    $query->orderBy('created_at', 'asc');
                }
            ])
            ->first();

        // Jika belum ada, buat baru
        if (!$room) {
            $room = ChatRoom::create([
                'kos_id' => $request->kos_id,
                'user_id' => $user->id
            ]);

            // Load relasi
            $room->load(['kos', 'kos.owner']);
        }

        return response()->json([
            'success' => true,
            'room' => $room
        ]);
    }

    public function getMessages($roomId)
    {
        $user = Auth::user();
        $room = ChatRoom::findOrFail($roomId);

        // Authorization
        if ($user->role === 'penyewa' && $room->user_id != $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        if ($user->role === 'pemilik' && $room->kos->user_id != $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = ChatMessage::where('chat_room_id', $roomId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }
}