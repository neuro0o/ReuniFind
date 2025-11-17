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

    protected $primaryKey = 'userID';
    protected $keyType = 'int';
    public $incrementing = true;

    // tell Laravel which column to use for auth
    public function getAuthIdentifierName()
    {
        return $this->primaryKey; // or 'userID'
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'userEmail',
        'userName',
        'password',
        'userRole',
        'contactInfo',
        'profileImg',
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

    

    /**
     * Get all handover requests initiated by this user (as the sender).
     */
    public function sentHandovers()
    {
        return $this->hasMany(HandoverRequest::class, 'senderID', 'userID');
    }

    /**
     * Get all handover requests received by this user (as the recipient).
     */
    public function receivedHandovers()
    {
        return $this->hasMany(HandoverRequest::class, 'recipientID', 'userID');
    }

}
