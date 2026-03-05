<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
use HasApiTokens, HasFactory, Notifiable;
    protected $guarded = [];

    public function doctor()
    {
        return $this->hasOne(doctor::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'patient_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'patient_id');
    }

    public function favorites()
    {
        return $this->belongsToMany(Doctor::class, 'favorites', 'patient_id', 'doctor_id');
    }

    public function searchHistories()
    {
        return $this->hasMany(SearchHistory::class, 'patient_id');
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'patient_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_user_id');
    }

    public function notificationLogs()
    {
        return $this->hasMany(NotificationLog::class);
    }

    public function otpCodes()
    {
        return $this->hasMany(OtpCode::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'actor_user_id');
    }

    public function conversationFavorites()
    {
        return $this->hasMany(ConversationFavorite::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'patient_id');
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

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
}
