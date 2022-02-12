<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Country;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory, SoftDeletes;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_name',
        'incorporation_date',
        'street',
        'street_number',
        'street2',
        'zip_code',
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
        'is_active',
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
    ];

    
    /**
     * Get the teachers.
     */
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }


    /**
     * Get the city for the user.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
   
    

}

