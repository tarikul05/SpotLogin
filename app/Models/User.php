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
use App\Models\SchoolEmployee;
use App\Models\SchoolTeacher;

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
    protected $appends = ['related_school'];



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
     * Get the teacher for the user.
     */
    public function coach()
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
     * Get the student for the user.
     */
    public function schooladmin()
    {
        
        return $this->belongsTo(SchoolEmployee::class, 'person_id', 'id');
        
    }


  


    /**
     * Get the posted user name
     *
     * @return object|null
     */
    public function getRelatedSchoolAttribute()
    {

        
        $school_data = '';

        switch ($this->person_type) {
            case 'TEACHER':
                if ($this->teacher) {
                    $school_data = !empty($this->teacher->schoolData) ? $this->teacher->schoolData : null;
                }
                break;
            case 'COACH':
                if ($this->coach) {
                    $school_data = !empty($this->coach) ? $this->coach : null;
                }
                break;
            case 'STUDENT':
                if ($this->student ) {
                    $school_data = !empty($this->student->schoolData) ? $this->student->schoolData : null;
                }
                break;
            case 'PARENT':
                if ($this->parent ) {
                    $school_data = !empty($this->parent->school) ? $this->parent->school : null;
                }
                break;
            case 'SCHOOL_ADMIN':
                if ($this->schooladmin ) {
                    $school_data = !empty($this->schooladmin->school) ? $this->schooladmin->school : null;
                }
                break;
            default:
                $school_data = null;
        }

        return $school_data;
    }

    static public function getUserData($field,$username,$password = null){
        
            return $user = self::where([
                [$field, $username],
                ['deleted_at', null],
                ['is_active', 1]
            ])->first();
        
    }

    static public function getUserDataDetails($field,$username,$password = null){
        
            return $user = self::where([
                [$field, $username],
                ['deleted_at', null],
                ['is_active', 1]
            ])->first()->toArray();
        
   }
}
