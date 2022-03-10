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
use App\Models\AttachedFile;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Traits\CreatedUpdatedBy;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles, SoftDeletes,CreatedUpdatedBy;

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
        'firstname',
        'lastname',
        'gender_id',
        'birth_date',
        'is_mail_sent',
        'is_reset_mail_requested',
        'profile_image_id',
        'user_authorisation',
        'school_id',
        'is_active',
        'is_firstlogin',
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
    protected $appends = ['related_school','selected_school','role_type'];



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
     * Get the personable for the user.
     */
    public function personable()
    {
        return $this->morphTo(__FUNCTION__, 'person_type', 'person_id');
    }

    
     /**
     * Get the schools for the user.
     */
    public function getRelatedSchoolAttribute()
    {
        if (!empty($this->school_id)) {
            return $this->personable->schools[0];
        } 
        return '';
        

    }

     /**
     * Get the schools for the user.
     */
    public function schools()
    {

        return $this->personable->schools;

    }
     /**
     * Get the schools for the user.
     */
    public function getSelectedSchoolAttribute()
    {

        return session('selected_school');

    }

     /**
     * Get the schools for the user.
     */
    public function selectedSchoolId()
    {
        $selectedSchool = self::getSelectedSchoolAttribute();
        return !empty($selectedSchool)? $selectedSchool->id : null;

    }

    /**
     * Get the schools for the user.
     */
    public function getRoleTypeAttribute()
    {
        if ($this->person_type =='SUPER_ADMIN') {
            return 'SUPER_ADMIN';
        }
        return !empty($this->related_school) ? $this->related_school->pivot->role_type : null ;
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
     * Get the user for the News.
    */
    public function profileImage()
    {
        return $this->belongsTo(AttachedFile::class, 'profile_image_id', 'id');
    }
  


    /**
     * Get the posted user name
     *
     * @return object|null
     */
    // public function getRelatedSchoolAttribute()
    // {

        
    //     $school_data = '';

    //     switch ($this->person_type) {
    //         case 'TEACHER':
    //             if ($this->teacher) {
    //                 $school_data = !empty($this->teacher->schoolData) ? $this->teacher->schoolData : null;
    //             }
    //             break;
    //         case 'COACH':
    //             if ($this->coach) {
    //                 $school_data = !empty($this->coach) ? $this->coach : null;
    //             }
    //             break;
    //         case 'STUDENT':
    //             if ($this->student ) {
    //                 $school_data = !empty($this->student->schoolData) ? $this->student->schoolData : null;
    //             }
    //             break;
    //         case 'PARENT':
    //             if ($this->parent ) {
    //                 $school_data = !empty($this->parent->school) ? $this->parent->school : null;
    //             }
    //             break;
    //         default:
    //             $school_data = null;
    //     }

    //     return $school_data;
    // }

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

    public function getFirstLoginData_after_reset($username,$password = null){
        
        //return $user = User::whereUsername($username)->wherePassword(Hash::make($password))->first();
        return $user = self::where([
            ['username', $username],
            ['is_firstlogin',1],
            ['deleted_at', null],
            ['is_active', 1]
        ])->first();

    }
    public function change_password($username,$old_password,$new_password){
        
        $user = self::where([
            ['username', $username],
            ['is_firstlogin',1],
            ['deleted_at', null],
            ['is_active', 1]
        ])->first();
        if (!$user) {
            return $result = array(
                'status' => 1,
                'message' => __('user not exist'),
            );
        }
        if (!Hash::check($old_password, $user->password)) {
            return $result = array(
                'status' => 1,
                'message' => __('Old password not matched'),
            );
        } 
        
        $user->password = $new_password;
        $user->is_firstlogin = 0;
        $user->save();
        return $result = array(
            'status' => 0,
            'message' => __('password reset done'),
        );
        

    }

   
}
