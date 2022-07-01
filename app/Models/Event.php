<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Models\Teacher;
use App\Models\School;
use App\Models\EventCategory;

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
      'event_price',
      'title',
      'description',
      'original_event_id',
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
    public function eventCategory()
    {
        return $this->belongsTo(EventCategory::class);
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
        $request = request();
        $authUser = $request->user();
        
        $fromFilterDate = null;
        $toFilterDate = null;

        if (isset($params['p_from_date'])) {
            $fromFilterDate = str_replace('/', '-',$params['p_from_date']);
        } 
        
        if (isset($params['p_to_date'])) {
            $toFilterDate = str_replace('/', '-', $params['p_to_date'])." 23:59";
        }


        $query->where('deleted_at', null);
        $query->where('is_locked', 0);
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
                        $query->whereIn($key, $value);
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

        try {

            if ($fromFilterDate && $toFilterDate) {
                
                if ($fromFilterDate && $toFilterDate) {
                    $query->where(function ($q) use ($fromFilterDate, $toFilterDate) {
                        $q->whereBetween('date_start', [$fromFilterDate, $toFilterDate])
                            ->orWhereBetween('date_end', [$fromFilterDate, $toFilterDate])
                            ->orWhere(function ($sq) use ($fromFilterDate, $toFilterDate) {
                                $sq->where('date_start', '<', $fromFilterDate)
                                    ->where('date_end', '>', $toFilterDate);
                            })
                            ;
                    });
                }
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
        $request = request();
        $authUser = $request->user();
        
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
            $toFilterDate = str_replace('/', '-', $params['p_to_date'])." 23:59";
            
            if (!$fromFilterDate) {
                $fromFilterDate = now();
            }
            unset($params['p_to_date']);
        }

        


        $query->where('deleted_at', null);
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
                        $query->whereIn($key, $value);
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

        try {

            if ($fromFilterDate && $toFilterDate) {
                
                if ($fromFilterDate && $toFilterDate) {
                    $query->where(function ($q) use ($fromFilterDate, $toFilterDate) {
                        $q->whereBetween('date_start', [$fromFilterDate, $toFilterDate])
                            ->orWhereBetween('date_end', [$fromFilterDate, $toFilterDate])
                            ->orWhere(function ($sq) use ($fromFilterDate, $toFilterDate) {
                                $sq->where('date_start', '<', $fromFilterDate)
                                    ->where('date_end', '>', $toFilterDate);
                            })
                            ;
                    });
                }
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
    public function filter($params)
    {

        $query = $this->newQuery();
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
        if (isset($params['start_date'])) {
            $fromFilterDate = $params['start_date'];
            if ($params['p_view'] =='CurrentListView') {
                $fromFilterDate = now();
            }
          
            //   if (!$toFilterDate) {
            //       $toFilterDate = now();
            //   }
            unset($params['start_date']);
        } 
      
        if (isset($params['end_date'])) {
           // $fromFilterDate = null;
            //$toFilterDate = null;
            
            $toFilterDate = $params['end_date'];
            if ($params['p_view'] =='CurrentListView') {
                $toFilterDate = str_replace('/', '-', $params['end_date'])." 23:59";
            }
          
            //   if (!$fromFilterDate) {
            //       $fromFilterDate = now();
            //   }
            unset($params['end_date']);
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
                        $query->whereIn($key, $value);
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
              $query->where(function ($q) use ($fromFilterDate, $toFilterDate) {
                  $q->whereBetween('date_start', [$fromFilterDate, $toFilterDate])
                      ->orWhereBetween('date_end', [$fromFilterDate, $toFilterDate])
                      ->orWhere(function ($sq) use ($fromFilterDate, $toFilterDate) {
                          $sq->where('date_start', '<', $fromFilterDate)
                              ->where('date_end', '>', $toFilterDate);
                      })
                      ;
              });
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
       //dd($params);
        
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
                        $query->whereIn($key, $value);
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
              $query->where(function ($q) use ($fromFilterDate, $toFilterDate) {
                  $q->whereBetween('date_start', [$fromFilterDate, $toFilterDate])
                      ->orWhereBetween('date_end', [$fromFilterDate, $toFilterDate])
                      ->orWhere(function ($sq) use ($fromFilterDate, $toFilterDate) {
                          $sq->where('date_start', '<', $fromFilterDate)
                              ->where('date_end', '>', $toFilterDate);
                      })
                      ;
              });
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
            ]);
        
        if (isset($params['school_id']) && !empty($params['school_id'])) {
            $teacherEvents->where('events.school_id', '=', $params['school_id']);
        }

        
        
        $user_role = $params['user_role'];
        if ($user_role == 'student') {
            $studentEvents->where('event_details.student_id', $params['person_id']);
        }
        if ($user_role == 'teacher') {
            $studentEvents->where('events.teacher_id', $params['person_id']);
        }


        $params['v_start_date'] = '2021-06-12';
        $query->where('events.date_start', '>=', $params['v_start_date']);
        $query->where('events.date_end', '<=', $params['v_end_date']);
        $query->distinct('events.id');
        //$query->groupBy('events.id');
        
       //dd($query->toSql());
       return $query;
    }

    
}
