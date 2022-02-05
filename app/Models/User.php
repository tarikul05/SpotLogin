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
use App\Models\Student;
use App\Models\Teacher;

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


     /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['related_person'];



    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }


    /**
     * Check user has USER authority
     * @return boolean
     */
    public function isUser()
    {

        return $this->authority !== 'SUPER_ADMIN';
    }


     /**
     * Get the teacher for the user.
     */
    public function teacher()
    {
        
        return $this->belongsTo(Teacher::class, 'person_id', 'id');
        
        
    }


     /**
     * Get the student for the user.
     */
    public function student()
    {
        
        return $this->belongsTo(Student::class, 'person_id', 'id');
        
    }

     /**
     * Get the student for the user.
     */
    public function parent()
    {
        
        return $this->belongsTo(Parent::class, 'person_id', 'id');
        
    }


  


    /**
     * Get the posted user name
     *
     * @return object|null
     */
    public function getRelatedPersonAttribute()
    {
        $person = '';

        switch ($this->person_type) {
            case 'TEACHER':
                if ($this->teacher) {
                    $person = !empty($this->teacher) ? $this->teacher : null;
                }
                break;
            case 'COACH':
                if ($this->teacher) {
                    $person = !empty($this->teacher) ? $this->teacher : null;
                }
                break;
            case 'STUDENT':
                if ($this->student ) {
                    $person = !empty($this->student) ? $this->student : null;
                }
                break;
            case 'PARENT':
                if ($this->parent ) {
                    $person = !empty($this->parent) ? $this->parent : null;
                }
                break;
            default:
                $person = null;
        }

        return $person=null;
    }


    static public function getUserData($field,$username,$password = null){
        if (!empty($password)) {

            // return self::with(['school'])
            //         ->whereHas('latestLoginLog', function ($q) {
            //             $q->whereNotNull('push_token')
            //                 ->where('push_token', '<>', '');
            //         })
            //         ->where('person_type', 'USER')
            //         ->where('is_verified', true)
            //         ->select('users.id');

                    
            return $user = self::where([
                [$field, $username],
                ['deleted_at', null],
                ['password', Hash::make($password)]
            ])->first()->toArray();
        } else {
            return $user = self::where([
                [$field, $username],
                ['deleted_at', null]
            ])->first()->toArray();
        }
   }
}
