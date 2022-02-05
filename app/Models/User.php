<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'person_id',
        'person_type',
        'username',
        'email',
        'password',
        'status',
        'first_name',
        'last_name',
        'gender_id',
        'birth_date',
        'is_mail_sent',
        'is_reset_mail_requested',
        'profile_image_id',
        'user_authorisation',
        'school_id',
        'is_active',
        'created_by',
        'modified_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];


    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }


    static public function getUserData($field,$username,$password){
        return $user = User::where([
                                       [$field, $username],
                                       ['deleted_at', null],
                                       ['password', Hash::make($password)],
                                   ])->first();
        //return $data = User::with(['shop'])->find($user->id);
   }
}
