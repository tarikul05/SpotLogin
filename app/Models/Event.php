<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Builder;
use App\Models\School;
use App\Models\EventCategory;
use App\Models\LessonPriceTeacher;

class Event extends BaseModel
{
    use HasFactory, SoftDeletes, CreatedUpdatedBy;
    protected $table = 'events';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'school_id',
      'visibility_id',
      'event_type',
      'event_category',
      'date_start',
      'date_end',
      'duration_minutes',
      'teacher_id',
      'is_paying',
      'student_is_paying',
      'event_price',
      'title',
      'description',
      'original_event_id',
      'event_invoice_type',
      'is_locked',
      'price_amount_sell',
      'price_currency',
      'price_amount_buy',
      'fullday_flag',
      'no_of_students',
      'event_mode',
      'extra_charges',
      'location_id',
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
     * The attributes that are partially match filterable.
     *
     * @var array
     */
    protected $arrayFilterable = [
        'school_id',
        'visibility_id',
        'event_type',
        'event_category',
        'duration_minutes',
        'is_paying',
        'student_is_paying',
        'event_price',
        'title',
        'original_event_id',
        'is_locked',
        'price_amount_sell',
        'teacher_id',
        'price_currency',
        'price_amount_buy',
        'fullday_flag',
        'no_of_students',
        'event_mode',
        'extra_charges',
        'location_id',
        'is_active',
        'created_by',
        'modified_by'
    ];


    /**
     * Get the teacher for event.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the teacher for event.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

     /**
     * Get the eventCategory for event.
     */
    public function eventcategory()
    {
        return $this->belongsTo(EventCategory::class,'event_category');
    }

    /**
     * Get the user for the News.
     */
    public function details()
    {
        return $this->hasMany(EventDetails::class);
    }



    /**
     * filter data based request parameters
     *
     * @param array $params
     * @return $query
     */
    public function multiDelete($params)
    {
        $query = $this->newQuery();
        if (empty($params) || !is_array($params)) {
            return $query;
        }
        $request = request();
        $user = $request->user();
        $user_role = self::checkUserRoesforQuery($user);
        $params['user_role'] = $user_role;
        $params['person_id'] = $user->person_id;


        $fromFilterDate = null;
        $toFilterDate = null;

        if (isset($params['p_from_date'])) {
            $fromFilterDate = str_replace('/', '-',$params['p_from_date']);
        }

        if (isset($params['p_to_date'])) {
            $toFilterDate = str_replace('/', '-', $params['p_to_date']);
        }

        if ($user_role == 'student') {
            $query->join('event_details', 'events.id', '=', 'event_details.event_id')
                ->select(['events.*']);
        }

        $query->select(['events.*'])
            ->where('events.deleted_at', null)
            ->where('events.is_locked', 0);

        foreach ($params as $key => $value) {
            if (!empty($value)) {

                if (in_array($key, $this->arrayFilterable)) {
                    if (isset($value) && strpos($value, '|') !== false){
                        $value = explode('|', $value);
                    }
                    if ($key=='teacher_id') {
                        //dd($value);
                    }
                    if (is_array($value)) {
                        $query->where(function ($query) use($key,$value) {
                            $query->whereIn($key, $value)
                                ->orWhereNull($key);
                        });
                        //$query->whereIn($key, $value);
                       // unset($params['authority:in']);
                    }  else {
                        $query->where("events.$key", '=', $value);
                    }

                    // $query->where($key, 'LIKE', "%{$value}%");
                }
                // else {
                //     $query->where($key, '=', $value);
                // }

            }
        }
        $user_role = $params['user_role'];
        if ($user_role == 'student') {
            $query->where('event_details.student_id', $params['person_id']);
        }

        $query->join('event_categories', 'events.event_category', '=', 'event_categories.id');
        if ($user_role == 'admin_teacher') {
                $query->where(function($query){
                        $query->where('events.event_invoice_type', 'S')
                                ->orWhere('event_categories.invoiced_type', 'S');

                    });
        }

        if ($user_role == 'teacher_all' || $user_role == 'teacher') {
            $query->where('events.teacher_id', $params['person_id']);
            $query->where(function($query){
                $query->Where('event_categories.invoiced_type', 'T')
                        ->orwhere(function($query){
                            $query->Where('events.event_invoice_type', 'T')
                                   ->Where('events.event_type', 100);
                        });
            });
        }
        $query->whereIn('events.event_type', [10,100]);

        try {

            if ($fromFilterDate && $toFilterDate) {
                $timeZone = 'UTC';
                if (!empty($params['school_id'])) {
                    $school = School::active()->find($params['school_id']);
                    if (!empty($school->timezone)) {
                        $timeZone = $school->timezone;
                    }
                }
                $fromFilterDate = $this->formatDateTimeZone($fromFilterDate.' 00:00:00', 'long',$timeZone,'UTC');

                $toFilterDate = $this->formatDateTimeZone($toFilterDate.' 23:59:59', 'long',$timeZone,'UTC');
                $qq = "events.date_start BETWEEN '" . date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $fromFilterDate))) . "' AND '" . date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $toFilterDate))) ."'";
                $query->whereRaw($qq);
            }
        } catch (\Exception $e) {

        }
        return $query;
    }


    /**
     * filter data based request parameters
     *
     * @param array $params
     * @return $query
     */
    public function multiValidate($params)
    {
        $query = $this->newQuery();
        if (empty($params) || !is_array($params)) {
            return $query;
        }
        $request = request();
        $user = $request->user();
        $user_role = self::checkUserRoesforQuery($user);
        $params['user_role'] = $user_role;
        $params['person_id'] = $user->person_id;





        $fromFilterDate = null;
        $toFilterDate = null;

        if (isset($params['p_from_date'])) {
            $fromFilterDate = str_replace('/', '-',$params['p_from_date']);

            if (!$toFilterDate) {
                $toFilterDate = now();
            }
            unset($params['p_from_date']);
        }

        if (isset($params['p_to_date'])) {
            $toFilterDate = str_replace('/', '-', $params['p_to_date']);

            if (!$fromFilterDate) {
                $fromFilterDate = now();
            }
            unset($params['p_to_date']);
        }



        if ($user_role == 'student') {
            $query->join('event_details', 'events.id', '=', 'event_details.event_id')
                ->select(['events.*']);
        }
        $query->select(['events.*'])
            ->where('events.deleted_at', null)
            ->where('events.is_locked', 0);

        foreach ($params as $key => $value) {
            if (!empty($value)) {

                if (in_array($key, $this->arrayFilterable)) {
                    if (isset($value) && strpos($value, '|') !== false){
                        $value = explode('|', $value);
                    }
                    if ($key =='user_role') {
                        //dd($value);
                    }
                    if (is_array($value)) {
                        $query->where(function ($query) use($key,$value) {
                            $query->whereIn($key, $value)
                                ->orWhereNull($key);
                        });
                        //$query->whereIn($key, $value);
                       // unset($params['authority:in']);
                    }  else {
                        $query->where("events.$key", '=', $value);
                    }

                    // $query->where($key, 'LIKE', "%{$value}%");
                }
                // else {
                //     $query->where($key, '=', $value);
                // }

            }
        }
        $user_role = $params['user_role'];
        if ($user_role == 'student') {
            $query->where('event_details.student_id', $params['person_id']);
        }

        $query->join('event_categories', 'events.event_category', '=', 'event_categories.id');
        if ($user_role == 'admin_teacher') {
                $query->where(function($query){
                    $query->where('events.event_invoice_type', 'S')
                            ->orWhere('event_categories.invoiced_type', 'S');
                });
        }

        if ($user_role == 'teacher_all' || $user_role == 'teacher') {
            $query->where('events.teacher_id', $params['person_id']);
            $query->where(function($query){
                $query->Where('event_categories.invoiced_type', 'T')
                        ->orwhere(function($query){
                            $query->Where('events.event_invoice_type', 'T')
                                   ->Where('events.event_type', 100);
                        });
            });
        }
        $query->whereIn('events.event_type', [10,100]);

        try {

            if ($fromFilterDate && $toFilterDate) {
                $timeZone = 'UTC';
                if (!empty($params['school_id'])) {
                    $school = School::active()->find($params['school_id']);
                    if (!empty($school->timezone)) {
                        $timeZone = $school->timezone;
                    }
                }

                $fromFilterDate = $this->formatDateTimeZone($fromFilterDate.' 00:00:00', 'long',$timeZone,'UTC');

                $toFilterDate = $this->formatDateTimeZone($toFilterDate.' 23:59:59', 'long',$timeZone,'UTC');
                $qq = "events.date_start BETWEEN '" . date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $fromFilterDate))) . "' AND '" . date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $toFilterDate))) ."'";
                $query->whereRaw($qq);
            }
        } catch (\Exception $e) {

        }
        return $query;
    }

    public function eventDetails()
    {
        return $this->hasMany(EventDetails::class);
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

        //query where end_date est different de start_date
        $query->whereColumn('events.date_end', '!=', 'events.date_start');

        $sortingParams = [];

        if (isset($params['sort'])) {
            $sortingParams = explode(',', $params['sort']);
            unset($params['sort']);
        }
        if (isset($params['type'])) {
            unset($params['type']);
        }
        if (isset($params['zone'])) {
            unset($params['zone']);
        }
        if (isset($params['start_date'])) {
            $fromFilterDate = $params['start_date'];
            if ($params['p_view'] =='CurrentListView') {
                $fromFilterDate = now();
            }

            //   if (!$toFilterDate) {
            //       $toFilterDate = now();
            //   }
            //unset($params['start_date']);
        }

        if (isset($params['end_date'])) {
           // $fromFilterDate = null;
            //$toFilterDate = null;

            $toFilterDate = $params['end_date'];
            if ($params['p_view'] =='CurrentListView') {
                $toFilterDate = str_replace('/', '-', $params['end_date']);
            }

            //   if (!$fromFilterDate) {
            //       $fromFilterDate = now();
            //   }
            unset($params['end_date']);
        }
        $user_role = $params['user_role'];
        if ($user_role == 'student') {
            $query->join('event_details', 'events.id', '=', 'event_details.event_id')
                ->select(['events.*']);
        }

        //$query->where('deleted_at', null);
        foreach ($params as $key => $value) {
            if (!empty($value)) {

                if (in_array($key, $this->arrayFilterable)) {
                    if (isset($value) && strpos($value, '|') !== false){
                        $value = explode('|', $value);
                    }
                    if ($key=='teacher_id') {
                        //dd($value);
                    }
                    if (is_array($value)) {

                        $query->where(function ($query) use($key,$value) {
                            $query->whereIn($key, $value)
                                ->orWhereNull($key);
                        });

                        //$query->whereIn($key, $value);
                       // unset($params['authority:in']);
                    }  else {
                        $query->where($key, '=', $value);
                    }

                    // $query->where($key, 'LIKE', "%{$value}%");
                }
                // else {
                //     $query->where($key, '=', $value);
                // }

            }
        }

        $schoolIdsArray = $params['schools'];

        $user_role = $params['user_role'];
        if ($user_role == 'student') {
            $query->where('event_details.student_id', $params['person_id']);
        }
        //if (!empty($params['schools'])) {
            $query->whereIn('events.school_id', $params['schools']);
        //}
        if ($user_role == 'teacher_minimum') {
            $query->where('events.teacher_id', $params['person_id']);
        }

        if (!empty($sortingParams)) {

            $column = null;
            $direction = null;

            foreach ($sortingParams as $sortingParam) {
                $columnAndDirection = explode(':', str_replace(' ', '', $sortingParam));

                if (!empty($columnAndDirection[0])) {
                    $column = $columnAndDirection[0];
                } else {
                    continue;
                }

                if (!empty($columnAndDirection[1])) {
                    $direction = $columnAndDirection[1];
                } else {
                    $direction = 'asc';
                }

                if (in_array($column, $this->fillable)) {
                    $query->orderBy($column, $direction);
                }
            }

        }



        try {
          if ($fromFilterDate && $toFilterDate) {
                $timeZone = 'UTC';
                if (!empty($params['school_id'])) {
                    $school = School::active()->find($params['school_id']);
                    if (!empty($school->timezone)) {
                        $timeZone = $school->timezone;
                    }
                }
                $fromFilterDate = $this->formatDateTimeZone($fromFilterDate.' 00:00:00', 'long',$timeZone,'UTC');

                //$toFilterDate = $this->formatDateTimeZone($toFilterDate.' 23:59:59', 'long',$timeZone,'UTC');
                //$qq = "events.date_start BETWEEN '" . date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $fromFilterDate))) . "' AND '" . date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $toFilterDate))) ."'";
                //$query->whereRaw($qq);

                $fromFilterDate = $this->formatDateTimeZone($params['start_date'].' 00:00:00', 'long', $timeZone, 'UTC');
                $toFilterDate = $this->formatDateTimeZone($toFilterDate.' 23:59:59', 'long', $timeZone, 'UTC');

                $qq = "(events.date_start BETWEEN '" . date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $fromFilterDate))) . "' AND '" . date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $toFilterDate))) ."') OR (events.date_start < '" . date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $fromFilterDate))) . "' AND events.date_end BETWEEN '" . date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $fromFilterDate))) . "' AND '" . date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $toFilterDate))) ."')";

                $query->whereRaw($qq);
          }
        } catch (\Exception $e) {

        }
        //dd($query->toSql());
        return $query;
    }






     /**
     * filter data based request parameters
     *
     * @param array $params
     * @return $query
     */
    public function filter_for_copy($params)
    {

        $query = $this->newQuery();
        //$request = request();
        if (empty($params) || !is_array($params)) {
            return $query;
        }


        $sortingParams = [];


        if (isset($params['sort'])) {
            $sortingParams = explode(',', $params['sort']);
            unset($params['sort']);
        }
        if (isset($params['type'])) {
            unset($params['type']);
        }
        if (isset($params['zone'])) {
            unset($params['zone']);
        }
        $request = request();
        $user = $request->user();
        $user_role = self::checkUserRoesforQuery($user);
        $params['user_role'] = $user_role;
        $params['person_id'] = $user->person_id;

       //dd($params);
        if ($user_role == 'student') {
            $query->join('event_details', 'events.id', '=', 'event_details.event_id')
                ->select(['events.*'])->where('event_details.student_id', $params['student_id']);

        }







        //$query->where('deleted_at', null);
        foreach ($params as $key => $value) {
            if (!empty($value)) {

                if (in_array($key, $this->arrayFilterable)) {
                    if (isset($value) && strpos($value, '|') !== false){
                        $value = explode('|', $value);
                    }
                    if ($key=='teacher_id') {
                        //dd($value);
                    }

                    /*if (is_array($value)) {
                        $query->where(function ($query) use($key,$value) {
                            $query->whereIn($key, $value)
                                ->orWhereNull($key);
                        });
                        $query->whereIn($key, $value);
                        unset($params['authority:in']);
                    }  else {
                        $query->where($key, '=', $value);
                    }*/


                    $query->where($key, '=', $value);


                    // $query->where($key, 'LIKE', "%{$value}%");
                }
                // else {
                //     $query->where($key, '=', $value);
                // }

            }
        }
        $user_role = $params['user_role'];
        if ($user_role == 'student') {
            $query->where('event_details.student_id', $params['person_id']);
        }
        if ($user_role == 'teacher') {
            $query->where('events.teacher_id', $params['person_id']);
        }



        if (!empty($sortingParams)) {

            $column = null;
            $direction = null;

            foreach ($sortingParams as $sortingParam) {
                $columnAndDirection = explode(':', str_replace(' ', '', $sortingParam));

                if (!empty($columnAndDirection[0])) {
                    $column = $columnAndDirection[0];
                } else {
                    continue;
                }

                if (!empty($columnAndDirection[1])) {
                    $direction = $columnAndDirection[1];
                } else {
                    $direction = 'asc';
                }

                if (in_array($column, $this->fillable)) {
                    $query->orderBy($column, $direction);
                }
            }

        }


        if (!empty($params['source_start_date'])) {
            //$fromFilterDate = null;
            // $toFilterDate = null;

            $fromFilterDate = $params['source_start_date'];

            // if (!$toFilterDate) {
            //     $toFilterDate = now();
            // }
        }

        if (!empty($params['source_end_date'])) {
            // $fromFilterDate = null;
            // $toFilterDate = null;
            $toFilterDate = $params['source_end_date'];

            // if (!$fromFilterDate) {
            //     $fromFilterDate = now();
            // }
        }
        try {
          if ($fromFilterDate && $toFilterDate) {
                $timeZone = 'UTC';
                if (!empty($params['school_id'])) {
                    $school = School::active()->find($params['school_id']);
                    if (!empty($school->timezone)) {
                        $timeZone = $school->timezone;
                    }
                }
                $fromFilterDate = $this->formatDateTimeZone($fromFilterDate.' 00:00:00', 'long',$timeZone,'UTC');

                $toFilterDate = $this->formatDateTimeZone($toFilterDate.' 23:59:59', 'long',$timeZone,'UTC');
                $qq = "events.date_start BETWEEN '" . date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $fromFilterDate))) . "' AND '" . date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $toFilterDate))) ."'";
                $query->whereRaw($qq);
          }
        } catch (\Exception $e) {

        }
        //dd($query->toSql());
        return $query;
    }









    /**
     * filter data based request parameters
     *
     * @param array $params
     * @return $query
     */
    public function filter_for_iCal($params)
    {
        //dd($params);
        $query = $this->newQuery();
        //$request = request();
        if (empty($params) || !is_array($params)) {
            return $query;
        }





        $query->join('event_details', 'events.id', '=', 'event_details.event_id')
        ->leftJoin('school_teacher', 'school_teacher.teacher_id', '=', 'event_details.teacher_id')
        ->leftJoin('event_categories', 'events.event_category', '=', 'event_categories.id')
        ->leftJoin('locations', 'locations.id', '=', 'events.location_id');
        //->leftJoin('users', 'users.person_id', '=', 'event_details.student_id')

        $query->select(
            'events.id as event_id',
            'school_teacher.nickname as teacher_name',
            'events.fullday_flag as fullday_flag',
            'events.date_start as date_start',
            'events.date_end as date_end',
            'events.title as event_title',
            'events.event_type as event_type',
            'event_categories.title as event_category_name',
            'locations.title as location_name'
        )
            ->selectRaw("concat(replace(events.id,'-',''),events.event_type,'@sportlogin') as id")
            ->selectRaw("if(events.fullday_flag='Y', date_format(ifnull(events.date_start,events.date_start),'%Y%m%d') ,date_format(events.date_start,'%Y%m%dT%H%i%sZ')) as start_datetime")
            ->selectRaw("if(events.fullday_flag='Y', date_format(DATE_ADD(ifnull(events.date_end,events.date_end),INTERVAL 1 DAY),'%Y%m%d') ,date_format(events.date_end,'%Y%m%dT%H%i%sZ')) as end_datetime")
            ->where(
                [
                    'events.is_active' => 1
                ]
            );

        if (isset($params['school_id']) && !empty($params['school_id'])) {
            $query->where('events.school_id', '=', $params['school_id']);
        }



        $user_role = $params['user_role'];
        if ($user_role == 'student') {
            $query->where('event_details.student_id', $params['person_id']);
        }
        if ($user_role == 'teacher') {
            $query->where('events.teacher_id', $params['person_id']);
        }


        $params['v_start_date'] = '2021-06-12';
        $query->where('events.date_start', '>=', $params['v_start_date']);
        $query->where('events.date_end', '<=', $params['v_end_date']);
        $query->distinct('events.id');
        //$query->groupBy('events.id');

        //dd($query->toSql());
        return $query;
    }



    public function priceCalculations($data=[])
    {
      $priceKey = isset($data['student_count']) && !empty($data['student_count']) ? ( $data['student_count'] > 10 ? 'price_su' : 'price_'.$data['student_count'] ) : '' ;

      $evtCategory = EventCategory::find($data['event_category_id']);
      $priceFixed = LessonPriceTeacher::active()->where(['event_category_id'=>$data['event_category_id'],'teacher_id'=>$data['teacher_id'],'lesson_price_student'=>'price_fix'])->first();

      $prices = LessonPriceTeacher::active()->where(['event_category_id'=>$data['event_category_id'],'teacher_id'=>$data['teacher_id'],'lesson_price_student'=>$priceKey])->first();
 // dd($priceKey,$prices);

      $buyPrice = $sellPrice = 0;
      if ($evtCategory->invoiced_type == 'S') {
        if (($evtCategory->s_thr_pay_type == 1) && ($evtCategory->s_std_pay_type == 1) ) {
          $buyPrice = isset($priceFixed->price_buy) ? $priceFixed->price_buy : 0;
          $sellPrice = isset($priceFixed->price_sell) ? $priceFixed->price_sell : 0;
        }elseif (($evtCategory->s_thr_pay_type == 1) && ($evtCategory->s_std_pay_type == 0) ) {
          $buyPrice = isset($priceFixed->price_buy) ? $priceFixed->price_buy : 0;
          $sellPrice = isset($prices->price_sell)? $prices->price_sell : 0;
        }elseif (($evtCategory->s_thr_pay_type == 0) && ($evtCategory->s_std_pay_type == 1) ) {
          $buyPrice = isset($prices->price_buy)? $prices->price_buy : 0;
          $sellPrice = isset($priceFixed->price_sell) ? $priceFixed->price_sell : 0;
        }elseif (($evtCategory->s_thr_pay_type == 1) && ($evtCategory->s_std_pay_type == 2) ) {
          $buyPrice = isset($priceFixed->price_buy) ? $priceFixed->price_buy : 0;
          $sellPrice = 0;
        }else{
          $buyPrice = isset($prices->price_buy)? $prices->price_buy : 0;
          $sellPrice = isset($prices->price_sell)? $prices->price_sell : 0;
        }
        if ($evtCategory->s_std_pay_type == 2) {
          $sellPrice = 0;
        }

      }else if ($evtCategory->invoiced_type == 'T') {
        if ($evtCategory->t_std_pay_type == 1) {
          $sellPrice = isset($priceFixed->price_sell)? $priceFixed->price_sell : 0;
        }elseif ( $evtCategory->t_std_pay_type == 0 ) {
          $sellPrice = isset($prices->price_sell)? $prices->price_sell : 0;
        }
      }

      // dd($buyPrice, $sellPrice);

      return ['price_buy'=>$buyPrice ,'price_sell'=>$sellPrice];
    }

    // update latest price with param event_id
    public function updateLatestPrice($event_id)
    {
        $eventData = Event::find($event_id);
        $studentCount = $eventData->no_of_students;
        // dd($eventData);
        $eventPrice = self::priceCalculations(['event_category_id'=> $eventData->event_category,'teacher_id'=>$eventData->teacher_id,'student_count'=>$eventData->no_of_students]);

        if(!empty($studentCount)){
            $buyPriceCal = ($eventPrice['price_buy']*($eventData->duration_minutes/60))/$studentCount;
        }else{
            $buyPriceCal = ($eventPrice['price_buy']*($eventData->duration_minutes/60));
        }
        $sellPriceCal = ($eventPrice['price_sell']*($eventData->duration_minutes/60));

        if($eventData['sis_paying'] == 1 && $eventData['student_sis_paying'] == 1 ){
           $attendBuyPrice = ($eventData->price_amount_buy*($eventData->duration_minutes/60))/$studentCount;
           $attendSellPrice = $eventData->price_amount_sell;
        }else{
            $attendBuyPrice = $buyPriceCal;
            $attendSellPrice = $sellPriceCal;
        }
        if ($eventData['student_sis_paying'] == 1) {
            $attendSellPrice = ($eventPrice['price_sell']*($eventData->duration_minutes/60));
        }
 // dd($eventData);
        if (isset($eventData->eventcategory->t_std_pay_type) && $eventData->eventcategory->t_std_pay_type == 1) {
             $attendSellPrice = $eventData->price_amount_sell*($eventData->duration_minutes/60);
        }

        $data = [
                'price_amount_buy' => $eventData->price_amount_buy,
                'price_amount_sell' => $eventData->price_amount_sell,
                'no_of_students' => $studentCount,
            ];

        $event = Event::where('id', $event_id)->update($data);
        $eventDetais = EventDetails::where('event_id',$event_id)->get();

        $attendSellPrice = round($attendSellPrice,2);
        $attendBuyPrice = round($attendBuyPrice,2);

        foreach($eventDetais as $evDtail){
            $dataDetails = [
                'buy_total' => $attendBuyPrice,
                'sell_total' => $attendSellPrice,
                'buy_price' => $attendBuyPrice,
                'sell_price' => $attendSellPrice,
            ];
            $eventDetails = EventDetails::where('id', $evDtail->id)->update($dataDetails);
        }

    }

    public static function validate($data=[], $lockStatus=1)
    {
      // dd($lockStatus, $data);

        try {
            $event_id = $data['event_id'];
            $eventUpdate = [
                'is_locked' => $lockStatus
            ];

            $eventData = Event::where('id', $event_id)->update($eventUpdate);
            $currentEvent = Event::find($event_id);


            $eventDetail = [
                'is_locked' => $lockStatus,
            ];

            $eventdetails = EventDetails::where('event_id', $event_id)->get();
            if (!empty($eventdetails)) {
                foreach ($eventdetails as $key => $eventdetail) {
                    $eventDetailPresent = [
                        'is_locked' => $lockStatus,
                        'participation_id' => 200,
                    ];

                    $eventDetailAbsent = [
                        'is_locked' => $lockStatus,
                        // 'participation_id' => 199,
                    ];
                    if ($eventdetail->participation_id !== 199) {
                        $eventdetail = $eventdetail->update($eventDetailPresent);
                    } else {
                        $eventdetail = $eventdetail->update($eventDetailAbsent);
                    }

                }
                if ($lockStatus && $currentEvent->event_type !== 100) {
                    Event::updateLatestPrice($event_id);
                }


                return true;
            }

        } catch (\Exception $e) {
            //return error message
            $result['message'] = __('Internal server error');
            return false;
        }
    }


    public function checkUserRoesforQuery($user)
    {
        $userRole = 'superadmin';
        if ($user->person_type == 'App\Models\Student') {
            $userRole = 'student';
        }
        if ($user->person_type == 'App\Models\Teacher') {
            $userRole = 'teacher';
        }
        if ($user->isSchoolAdmin()  || $user->isTeacherAdmin()) {
            $userRole = 'admin_teacher';
        }
        if ($user->isTeacherAll()) {
            $userRole = 'teacher_all';
        }
        if ($user->isTeacherMedium() || $user->isTeacherMinimum() || $userRole =='teacher'|| $user->isTeacherSchoolAdmin()) {
            $userRole = 'teacher';
        }

        return $userRole;

    }


}
