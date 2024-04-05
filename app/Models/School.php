<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Country;
use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;


class School extends BaseModel
{
    use SoftDeletes, CreatedUpdatedBy;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_name',
        'timezone',
        'legal_status',
        'incorporation_date',
        'street',
        'street_number',
        'street2',
        'zip_code',
        'place',
        'country_code',
        'province_id',
        'phone',
        'phone2',
        'mobile',
        'mobile2',
        'email',
        'email2',
        'logo_image_id',
        'bank_iban',
        'bank_account',
        'bank_swift',
        'bank_name',
        'bank_address',
        'bank_zipcode',
        'bank_place',
        'bank_country_code',
        'bank_province_id',
        'contact_gender_id',
        'contact_lastname',
        'contact_firstname',
        'contact_position',
        'bank_account_holder',
        'status',
        'school_code',
        'default_currency_code',
        'sender_email',
        'billing_method',
        'billing_amount',
        'billing_method_eff_date',
        'billing_currency',
        'billing_date_start',
        'billing_date_end',
        'max_students',
        'max_teachers',
        'school_type',
        'tax_desc',
        'tax_perc',
        'tax_applicable',
        'etransfer_acc',
        'cheque_payee',
        'tax_number',
        'invoice_opt_activated',
        'discipline',
        'is_active',
        'number_of_coaches',
        'created_by',
        'modified_by'
    ];



    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'date:Y/m/d H:i',
        'modified_at' => 'date:Y/m/d H:i',
        'incorporation_date' => 'date:Y/m/d',
        'billing_date_start'=> 'date:Y/m/d',
        'billing_date_end'=> 'date:Y/m/d'
    ];

     /**
     * Get the Teachers for the Schools.
     */
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class)
                    ->whereNull('school_teacher.deleted_at')
                    ->withPivot( 'nickname', 'licence_js', 'role_type', 'is_teacher', 'has_user_account', 'bg_color_agenda', 'comment', 'is_active', 'created_at','deleted_at');
    }


    /**
     * Get the Students for the Schools.
     */
    public function students()
    {
        return $this->belongsToMany(Student::class)
                    ->whereNull('school_student.deleted_at')
                    ->withPivot( 'nickname', 'email', 'billing_method', 'level_id', 'has_user_account', 'licence_arp', 'level_skating_arp', 'level_date_arp', 'licence_usp', 'level_skating_usp', 'level_date_usp', 'comment', 'is_active', 'created_at','deleted_at');
    }


    /**
     * Get invoices for the Schools.
     */
    public function invoices()
    {
        return $this->belongsTo(Invoice::class);
    }


    /**
     * Get the city for the user.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }


    /**
     * Get the logo image.
    */
    public function logoImage()
    {
        return $this->belongsTo(AttachedFile::class, 'logo_image_id', 'id');
    }


     /**
     * filter data based request parameters
     *
     * @param array $params
     * @return $query
     */
    public function filter($params)
    {
        $query = $this->newQuery();
        if (empty($params) || !is_array($params)) {
            return $query;
        }
        if (isset($params['sort']) && !empty($params['sort'])) {
            $sortExplode = explode('-', $params['sort']);
            $query->orderBy($sortExplode[0],$sortExplode[1]);
        } else {
            $query->orderBy('id', 'desc');
        }
        return $query;
    }


}

