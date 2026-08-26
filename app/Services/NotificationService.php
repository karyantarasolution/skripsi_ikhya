<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    public static function send(User $recipient, string $type, string $title, string $body, string $url = ''): void
    {
        DB::table('notifications')->insert([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'type' => $type,
            'notifiable_type' => User::class,
            'notifiable_id' => $recipient->id,
            'data' => json_encode([
                'title' => $title,
                'body' => $body,
                'url' => $url,
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public static function sendToRole(string $role, string $type, string $title, string $body, string $url = ''): void
    {
        $users = User::where('role', $role)->get();
        foreach ($users as $user) {
            self::send($user, $type, $title, $body, $url);
        }
    }

    public static function getUnreadCount(User $user): int
    {
        return DB::table('notifications')
            ->where('notifiable_type', User::class)
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->count();
    }

    public static function getLatest(User $user, int $limit = 10): array
    {
        return DB::table('notifications')
            ->where('notifiable_type', User::class)
            ->where('notifiable_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($n) {
                $n->data = json_decode($n->data);
                return $n;
            })
            ->toArray();
    }

    public static function markAsRead(string $notificationId, User $user): bool
    {
        return DB::table('notifications')
            ->where('id', $notificationId)
            ->where('notifiable_type', User::class)
            ->where('notifiable_id', $user->id)
            ->update(['read_at' => now()]) > 0;
    }

    public static function markAllAsRead(User $user): int
    {
        return DB::table('notifications')
            ->where('notifiable_type', User::class)
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
