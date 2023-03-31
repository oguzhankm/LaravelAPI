<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 *
 * @OA\Schema(
 *     title = "User",
 *     description = "User model",
 *     type="object",
 *     schema="User",
 *     @OA\Property (property="id",type="integer",format="int64"),
 *     @OA\Property (property="name", type="string"),
 *     @OA\Property (property="email", type="string"),
 *     @OA\Property (property="password", type=""),
 *     @OA\Property (property="firstname", type="string"),
 *     @OA\Property (property="lastname", type="string"),
 *     required={"id", "name","email"},
 *
 * )
 */

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'first_name',
        'last_name',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
//        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'date:Y-m-d'
    ];

    /**
     * @var string[]
     */
    protected $appends = ['full_name'];

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name ." " . $this->last_name;
    }




}
