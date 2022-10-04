<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;

class Invoice extends BaseModel
{
    use HasFactory, SoftDeletes, CreatedUpdatedBy;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id',
        'invoice_no',
        'invoice_type',
        'invoice_status',
        'date_invoice',
        'peroid_starts',
        'period_ends',
        'fully_paid_date',
        'invoice_name',
        'invoice_header',
        'invoice_footer',
        'client_id',
        'client_name',
        'client_gender_id',
        'client_firstname',
        'client_street',
        'client_street_number',
        'client_street2',
        'client_zip_code',
        'client_place',
        'client_country_code',
        'seller_id',
        'seller_name',
        'seller_gender_id',
        'seller_lastname',
        'seller_firstname',
        'seller_street',
        'seller_street_number',
        'seller_street2',
        'seller_zip_code',
        'seller_place',
        'seller_country_code',
        'seller_phone',
        'seller_mobile',
        'seller_email',
        'seller_eid',
        'payment_bank_account_name',
        'payment_bank_iban',
        'payment_bank_account',
        'payment_bank_swift',
        'payment_bank_name',
        'payment_bank_address',
        'payment_bank_zipcode',
        'payment_bank_place',
        'payment_bank_country_code',
        'subtotal_amount_all',
        'subtotal_amount_no_discount',
        'subtotal_amount_with_discount',
        'discount_percent_1',
        'discount_percent_2',
        'discount_percent_3',
        'discount_percent_4',
        'discount_percent_5',
        'discount_percent_6',
        'amount_discount_1',
        'amount_discount_2',
        'amount_discount_3',
        'amount_discount_4',
        'amount_discount_5',
        'amount_discount_6',
        'total_amount_discount',
        'total_amount_no_discount',
        'total_amount_with_discount',
        'total_vat',
        'vat_percent',
        'total_amount',
        'invoice_filename',
        'extra_expenses',
        'approved_flag',
        'payment_status',
        'invoice_creation_type',
        'language_code',
        'billing_method',
        'invoice_currency ',
        'tax_desc',
        'tax_perc',

        'tax_amount',
        'etransfer_acc',
        'cheque_payee',
        'client_province_id',
        'seller_province_id',
        'bank_province_id',
        'e_transfer_email',
        'name_for_checks',
        'category_invoiced_type',
        'created_at',
        'modified_at',
        'deleted_at',


        'approved_flag',
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
    protected $appends = [];
    /**
     * Get the schools for the Student.
     */
    public function schools()
    {
        return $this->belongsToMany(School::class)
            ->withPivot('id', 'nickname', 'billing_method', 'has_user_account', 'level_id', 'licence_arp', 'level_skating_arp', 'level_date_arp', 'licence_usp', 'level_skating_usp', 'level_date_usp', 'comment', 'is_active', 'created_at', 'deleted_at');
    }
}
