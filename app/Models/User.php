<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function dokumentasi()
    {
        return $this->hasMany(Dokumentasi::class, 'user_id');
    }

    public function penugasan()
    {
        return $this->hasMany(PenugasanLiputan::class, 'user_id');
    }

    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'user_id');
    }

    public function approvalKegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'approved_by');
    }

    public function suratPerjalananDinas()
    {
        return $this->hasMany(SuratPerjalananDinas::class, 'user_id');
    }

    public function lpjTugas()
    {
        return $this->hasMany(LpjTugas::class, 'user_id');
    }
}
