<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use Carbon\Carbon;
use DB;

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
        'period_starts',
        'period_ends',
        'fully_paid_date',
        'invoice_name',
        'invoice_header',
        'invoice_footer',
        'client_id',
        'client_name',
        'client_gender_id',
        'client_firstname',
        'client_lastname',
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

    /**
     * Get the schools for the Student.
     */
    public function school()
    {
        return $this->BelongsTo(School::class, 'school_id');
    }

    /**
     * @return HasMany
     */
    public function invoice_items(): HasMany
    {
        return $this->HasMany(InvoiceItem::class, 'invoice_id');
    }


    /**
     * getStudentInvoiceList for invoicing
     * 
     * @param array $params
     * @return $query
     */
    public function getStudentInvoiceList($user,$schoolId,$user_role,$invoice_type)
    {
        $studentEvents = DB::table('events')
            ->join('event_details', 'events.id', '=', 'event_details.event_id')
            ->leftJoin('event_categories', 'event_categories.id', '=', 'events.event_category')
            ->leftJoin('school_student', 'school_student.student_id', '=', 'event_details.student_id')
            ->leftJoin('users', 'users.person_id', '=', 'event_details.student_id')
            ->select(
                'events.id as event_id',
                'event_details.student_id as person_id',
                'school_student.nickname as student_name',
                'users.profile_image_id as profile_image_id'
            )
            ->where(
                [
                    'events.school_id' => $schoolId,
                    'event_details.billing_method' => "E",
                    'events.is_active' => 1
                ]
            );
        
        if ($user_role == 'admin_teacher' || $user_role == 'coach_user') {
            $studentEvents->where('event_categories.invoiced_type', $invoice_type);
        } else if ($user_role == 'teacher_all') {
            $studentEvents->where('event_categories.invoiced_type', $invoice_type);
        } else if ($user_role == 'teacher') {
            $studentEvents->where('event_categories.invoiced_type', $invoice_type);
            $studentEvents->where('events.teacher_id', $user->person_id);
        } else {
        }
        $studentEvents->where('event_details.is_sell_invoiced', '=', 0);
        $studentEvents->whereNull('event_details.sell_invoice_id');

        $dateS = Carbon::now()->startOfMonth()->subMonth(1)->format('Y-m-d');
        $studentEvents->where('events.date_start', '>=', $dateS);
        $studentEvents->distinct('events.id');
        //$studentEvents->groupBy('event_details.student_id');
        //dd($studentEvents->toSql());

        return $allEvents = DB::table(DB::raw('(' . $studentEvents->toSql() . ') as custom_table'))
            ->select(
                'custom_table.person_id as person_id',
                'custom_table.student_name as student_name',
                'custom_table.profile_image_id as profile_image_id'
            )
            ->selectRaw('count(custom_table.event_id) as invoice_items')
            ->mergeBindings($studentEvents)
            ->groupBy('custom_table.person_id')
            ->get();
    }


    /**
     * getTeacherInvoiceList for invoicing
     * 
     * @param array $params
     * @return $query
     */
    public function getTeacherInvoiceList($user,$schoolId,$user_role,$invoice_type)
    {
        $teacherEvents = DB::table('events')
            ->join('event_details', 'events.id', '=', 'event_details.event_id')
            ->leftJoin('event_categories', 'event_categories.id', '=', 'events.event_category')
            ->leftJoin('school_teacher', 'school_teacher.teacher_id', '=', 'event_details.teacher_id')
            ->leftJoin('teachers', 'teachers.id', '=', 'event_details.teacher_id')
            ->leftJoin('users', 'users.person_id', '=', 'event_details.teacher_id')
            ->select(
                'event_details.id as detail_id',
                'events.id as event_id',
                'event_details.student_id as student_id',
                'event_details.teacher_id as person_id',
                'users.profile_image_id as profile_image_id'
            )
            ->selectRaw('count(event_details.id) as invoice_items')
            ->selectRaw("CONCAT_WS(' ', teachers.firstname, teachers.middlename, teachers.lastname) AS teacher_name")
            //->selectRaw('count(events.id) as invoice_items')
            ->where(
                [
                    'events.school_id' => $schoolId,
                    'event_details.billing_method' => "E",
                    'events.is_active' => 1
                ]
            );
        if ($user_role == 'admin_teacher' || $user_role == 'coach_user') {
            $teacherEvents->where('event_categories.invoiced_type', $invoice_type);
        } else if ($user_role == 'teacher_all') {
            $teacherEvents->where('event_categories.invoiced_type', $invoice_type);
        } else if ($user_role == 'teacher') {
            $teacherEvents->where('event_categories.invoiced_type', $invoice_type);
            $teacherEvents->where('events.teacher_id', $user->person_id);
        } else {
        }
        $teacherEvents->where('event_details.visibility_id', '>', 0);
        $teacherEvents->where('event_details.is_buy_invoiced', '=', 0);
        $teacherEvents->whereNull('event_details.buy_invoice_id');


        $dateS = Carbon::now()->startOfMonth()->subMonth(1)->format('Y-m-d');
        $dateEnd = Carbon::now()->subMonth(0)->format('Y-m-d');
        $qq = "events.date_start BETWEEN '" . $dateS . "' AND '" . $dateEnd . "'";
        
        $teacherEvents->whereRaw($qq);
        $teacherEvents->distinct('event_details.id');
        $teacherEvents->groupBy('event_details.event_id');

        //dd($teacherEvents->toSql());
        $allEventData =  $teacherEvents->get();
        $allEvents = DB::table(DB::raw('(' . $teacherEvents->toSql() . ') as custom_table'))
            ->select(
                'custom_table.person_id as person_id',
                'custom_table.teacher_name as teacher_name',
                'custom_table.profile_image_id as profile_image_id'
            )
            ->selectRaw('count(custom_table.event_id) as invoice_items')
            ->mergeBindings($teacherEvents)
            ->distinct('custom_table.detail_id')
            ->groupBy('custom_table.person_id');
        //dd($allEvents->toSql());
        return $allEvents->get();
    }

    /**
     * getStudentEventList for invoicing
     * 
     * @param array $params
     * @return $query
     */
    public function getStudentEventList($user,$p_person_id,$p_school_id,$user_role,$invoice_type)
    {
        $studentEvents = DB::table('events')
                ->join('event_details', 'events.id', '=', 'event_details.event_id')
                ->leftJoin('event_categories', 'event_categories.id', '=', 'events.event_category')
                ->leftJoin('teachers', 'teachers.id', '=', 'event_details.teacher_id')
                ->leftJoin('students', 'students.id', '=', 'event_details.student_id')
                ->leftJoin('users', 'users.person_id', '=', 'event_details.teacher_id')
                ->select(
                    'events.id as event_id',
                    'event_details.buy_total as buy_total',
                    'event_details.sell_total as sell_total',
                    'event_details.buy_price as buy_price',
                    'event_details.sell_price as sell_price',
                    'events.title as title',
                    'events.event_type as event_type',
                    'events.event_category as category_id',
                    'event_categories.title as category_name',
                    'events.is_paying as is_paying',
                    'events.event_price as price_id',
                    'event_details.is_locked as ready_flag',
                    'event_details.participation_id as participation_id',
                    'event_details.is_buy_invoiced as is_buy_invoiced',
                    'event_details.is_sell_invoiced as is_sell_invoiced',
                    'event_details.costs_1 as costs_1',
                    'events.extra_charges as extra_charges',
                    'event_details.costs_2 as costs_2',
                    'teachers.id as teacher_id'
                )
                ->selectRaw("ifnull(events.duration_minutes,0) AS duration_minutes")
                ->selectRaw("ifnull(event_details.price_currency,'CAD') AS price_currency")
                ->selectRaw("if((events.event_type = 100),'Event','Lesson') AS price_name")
                ->selectRaw("CONCAT_WS('', students.firstname, students.middlename, students.lastname)  AS student_name")
                ->selectRaw("CONCAT_WS('', teachers.firstname, teachers.middlename, teachers.lastname) AS teacher_name")
                ->selectRaw('DATE_FORMAT(str_to_date(concat("01/",month(events.date_start),"/",year(events.date_start)),"%d/%m/%Y"),"%d/%m/%Y") as FirstDay')
                ->selectRaw('DATE_FORMAT(str_to_date(concat("30/",month(events.date_start),"/",year(events.date_start)),"%d/%m/%Y"),"%d/%m/%Y") as Lastday')
                ->selectRaw('DATE_FORMAT(events.date_start,"%H:%i") time_start')
                ->selectRaw('DATE_FORMAT(events.date_start,"%d/%m/%Y") date_start')
                ->selectRaw('week(events.date_start,5) week_no')
                ->selectRaw('concat("Semaine ",week(events.date_start,5)) as week_name')
                //->selectRaw('count(events.id) as invoice_items')
                ->where(
                    [
                        'event_details.student_id' => $p_person_id,
                        'event_details.billing_method' => "E",
                        'events.is_active' => 1,
                        'events.school_id' => $p_school_id,
                    ]
                );

            

            // dd($user);
            if ($user_role == 'admin_teacher' || $user_role == 'coach_user') {
                
                $studentEvents->where('event_categories.invoiced_type', $invoice_type);
            } else if ($user_role == 'teacher_all') {
                
                $studentEvents->where('event_categories.invoiced_type', $invoice_type);
            } else if ($user_role == 'teacher') {
                
                $studentEvents->where('event_categories.invoiced_type', $invoice_type);
                $studentEvents->where('events.teacher_id', $user->person_id);
            } else {
            }
            
            

            $qq = "events.date_start BETWEEN '" . date('Y-m-d', strtotime(str_replace('/', '-', $p_billing_period_start_date))) . "' AND '" . date('Y-m-d', strtotime(str_replace('/', '-', $p_billing_period_end_date))) . "'";
            $studentEvents->whereRaw($qq);


            //$studentEvents->where('events.date_start', '>=', $dateS);
            
            $studentEvents->whereNull('events.deleted_at');
            $studentEvents->whereNull('event_details.deleted_at');

            return $studentEvents;
    }

}
