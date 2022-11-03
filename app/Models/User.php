<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use DateTimeInterface;
use App\Models\Transaction;
use App\Observers\UserObserver;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const INITIAL_BALANCE = 1000;

    const SUPER_ADMIN = 'Super Admin';
    const SUBSCRIBER = 'Subscriber';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'currency',
        'password',
        'remember_token',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'deleted_at',
        'remember_token',
        'email_verified_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get all of the send money transactions associated with the user.
     */
    public function sendMoney(){
        return $this->hasMany(Transaction::class, 'sender_id', 'id');
    }

    /**
     * Get all of the received money transactions associated with the user.
     */
    public function receivedMoney(){
        return $this->hasMany(Transaction::class, 'receiver_id', 'id');
    }


    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->diffForHumans();
    }
}
