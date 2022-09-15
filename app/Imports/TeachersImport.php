<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\School;
use App\Models\Teacher;
use App\Models\SchoolTeacher;
use Illuminate\Support\Facades\Log;


class TeachersImport implements ToModel, WithHeadingRow
{
    // use Importable;

    private $school_id;

    private $recordUpdated = 0;
    private $recordInserted = 0;

    public function __construct(int $school_id) 
    {
        $this->school_id = $school_id;
    }


    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row);
        $data = [
            'email' => $row['email'],
            'lastname' => $row['family_name'],
            'firstname' => $row['firstname'],
            'nickname' => $row['nickname'],
            'gender_id' => strtolower($row['gender']) == 'male' ? 1 : (strtolower($row['gender']) == 'female' ? 2 : 3) ,
            'licence_js' => $row['licence'],
            'bg_color_agenda' => $row['background_color'],
            'comment' => $row['comment'],
            'birth_date' => !empty($row['birth_date']) ? date('Y-m-d H:i:s',strtotime($row['birth_date'])) : null,
            'street' => $row['street'],
            'street_number' => $row['street_no'],
            'zip_code' => $row['postal_code'],
            'place' => $row['city'],
            'phone' => $row['phone'],
            'mobile' => $row['mobile'],
        ];
        if (!empty($row['email'])) {
            $this->dataFormate($data);
        }
    }

    public function getMessage()
    {
        return "There is $this->recordInserted record inserted and $this->recordUpdated record updated.";
    }

    public function dataFormate($data=[])
    {
        $teacherData = [
            'email' => $data['email'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'gender_id' => $data['gender_id'],
            'birth_date' => $data['birth_date'],
            'licence_js' => $data['licence_js'],
            'street' => $data['street'],
            'street_number' => $data['street_number'],
            'zip_code' => $data['zip_code'],
            'place' => $data['place'],
            'phone' => $data['phone'],
            'mobile' => $data['mobile'],
        ];
        $schoolTeacherData = [
            'school_id' => $this->school_id,
            'role_type' => 'teachers_all',
            'nickname' => $data['nickname'],
            'bg_color_agenda'=>$data['bg_color_agenda'],
            'comment' => $data['comment'],
            'is_active' => 1,
            // 'is_sent_invite' => 0,
        ];
Log::info("Import Teachers ".$data['email']." in schoolId=".$this->school_id);
        $teacherExist = Teacher::where(['email'=> $data['email']])->first();
        $schoolTeacherExist = SchoolTeacher::where(['teacher_id'=> !empty($teacherExist)? $teacherExist->id : null , 'school_id'=> $this->school_id])->first();
        // dd($teacherExist,$schoolTeacherExist, $schoolTeacherData );
        if (empty($teacherExist)) {
            DB::beginTransaction();
            try {
                $teacher = Teacher::create($teacherData);
                $schoolTeacherData['teacher_id'] = $teacher->id;
                $teacherSchool = SchoolTeacher::create($schoolTeacherData);
               DB::commit(); 
                ++$this->recordInserted;
            } catch (Exception $e) {
                DB::rollBack();
                Log::error("import Teachers email: ".$data['email']." failed 1st condtions in schoolId=".$this->school_id);
            }
                
        }elseif (empty($schoolTeacherExist) && !empty($teacherExist)) {
            DB::beginTransaction();
            try {
                $schoolTeacherData['teacher_id'] = $teacherExist->id;
                $teacherSchool = SchoolTeacher::create($schoolTeacherData);
                $teacher = Teacher::where('id', $teacherExist->id)->update($teacherData);
               DB::commit(); 
                ++$this->recordInserted;
            } catch (Exception $e) {
                DB::rollBack();
                Log::error("import Teachers email: ".$data['email']." failed 2nd condtions in schoolId=".$this->school_id);
            }
            
        }elseif (!empty($schoolTeacherExist) && !empty($teacherExist)) {
            DB::beginTransaction();
            try {
                $teacher = Teacher::where('id', $teacherExist->id)->update($teacherData);
                $teacherSchool = SchoolTeacher::where(['teacher_id'=> $teacherExist->id, 'school_id'=> $this->school_id])->update($schoolTeacherData);
                DB::commit(); 
                ++$this->recordUpdated;
            } catch (Exception $e) {
                DB::rollBack();
                Log::error("import Teachers email: ".$data['email']." failed 3rd condtions in schoolId=".$this->school_id);
            }
        }else{
           Log::info("Not updated Teachers ".$data['email']." in schoolId=".$this->school_id); 
        }
    }
}
