<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\School;
use App\Models\Student;
use App\Models\SchoolStudent;
use Illuminate\Support\Facades\Log;


class StudentsImport implements ToModel, WithHeadingRow
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
            'email' => !empty($row['email']) ? $row['email'] : 'student@sportlogin.app',
            'lastname' => !empty($row['family_name']) ? $row['family_name'] : '',
            'firstname' => !empty($row['firstname']) ? $row['firstname'] : '',
            'nickname' => !empty($row['nickname']) ? $row['nickname'] : '',
            'gender_id' => !empty($row['gender']) ? strtolower($row['gender']) == 'male' ? 1 : (strtolower($row['gender']) == 'female' ? 2 : 3):3,
            'licence_usp' => !empty($row['licence']) ? $row['licence'] : '',
            'comment' => !empty($row['comment']) ? $row['comment'] : '',
            'birth_date' => !empty($row['birth_date']) ? date('Y-m-d H:i:s',strtotime($row['birth_date'])) : null,
            'street' => !empty($row['street']) ? $row['street'] : '',
            'street_number' => !empty($row['street_no']) ? $row['street_no'] : '',
            'zip_code' => !empty($row['postal_code']) ? $row['postal_code'] : '',
            'place' => !empty($row['city']) ? $row['city'] : '',

            'billing_street' => !empty($row['billing_street']) ? $row['billing_street'] : '',
            'billing_street_number' => !empty($row['billing_street_no']) ? $row['billing_street_no'] : '',
            'billing_zip_code' => !empty($row['billing_postal_code']) ? $row['billing_postal_code'] : '',
            'billing_place' => !empty($row['billing_city']) ? $row['billing_city'] : '',

            'father_phone' => !empty($row['fathers_phone']) ? $row['fathers_phone'] : '',
            'father_email' => !empty($row['fathers_email']) ? $row['fathers_email'] : '',
            'mother_phone' => !empty($row['mothers_phone']) ? $row['mothers_phone'] : '',
            'mother_email' => !empty($row['mothers_email']) ? $row['mothers_email'] : '',


            'mobile' => !empty($row['students_phone']) ? $row['students_phone'] : '',
            'email2' => !empty($row['students_2nd_email']) ? $row['students_2nd_email'] : '',
        ];

            if (empty($row['email'])) {
                $data['email'] = 'student@sportlogin.app';
            }

            $this->dataFormate($data);

    }

    public function getMessage()
    {
        return "There is $this->recordInserted record inserted and $this->recordUpdated record updated.";
    }

    public function dataFormate($data=[])
    {
        // dd($data);
        $studentData = [
            'email' => $data['email'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'gender_id' => $data['gender_id'],
            'birth_date' => $data['birth_date'],
            'street' => $data['street'],
            'street_number' => $data['street_number'],
            'zip_code' => $data['zip_code'],
            'place' => $data['place'],

            'billing_street' => $data['billing_street'],
            'billing_street_number' => $data['billing_street_number'],
            'billing_zip_code' => $data['billing_zip_code'],
            'billing_place' => $data['billing_place'],

            'father_phone' => $data['father_phone'],
            'father_email' => $data['father_email'],
            'mother_phone' => $data['mother_phone'],
            'mother_email' => $data['mother_email'],

            'mobile' => $data['mobile'],
            'email2' => $data['email2'],
        ];
        $schoolStudentData = [
            'school_id' => $this->school_id,
            'nickname' => $data['nickname'],
            'comment' => $data['comment'],
            'licence_usp' => $data['licence_usp'],
            'is_active' => 1,
            // 'is_sent_invite' => 0,
        ];
Log::info("Import Student ".$data['email']." in schoolId=".$this->school_id);
        $stdExist = Student::where('email', $data['email'])
        ->where('firstname', $data['firstname'])
        ->where('lastname', $data['lastname'])
        ->first();
        $schoolStdExist = SchoolStudent::where(['student_id'=> !empty($stdExist)? $stdExist->id : null , 'school_id'=> $this->school_id])->first();
        // dd($stdExist,$schoolStdExist, $studentData ,$schoolStudentData );
        if (empty($stdExist)) {
            DB::beginTransaction();
            try {
                $teacher =Student::create($studentData);
                $schoolStudentData['student_id'] = $teacher->id;
                $teacherSchool = SchoolStudent::create($schoolStudentData);
               DB::commit(); 
               ++$this->recordInserted;
               Log::info("student and school_student new entry");
            } catch (Exception $e) {
                DB::rollBack();
                Log::error("import Student email: ".$data['email']." failed 1st condtions in schoolId=".$this->school_id);
            }
                
        }elseif (empty($schoolStdExist) && !empty($stdExist)) {
            DB::beginTransaction();
            try {
                $schoolStudentData['student_id'] = $stdExist->id;
                $teacherSchool = SchoolStudent::create($schoolStudentData);
                $teacher =Student::where('id', $stdExist->id)->update($studentData);
               DB::commit(); 
               ++$this->recordInserted;
               
               Log::info("student update and school_student new entry");
            } catch (Exception $e) {
                DB::rollBack();
                Log::error("import Student email: ".$data['email']." failed 2nd condtions in schoolId=".$this->school_id);
            }
            
        }elseif (!empty($schoolStdExist) && !empty($stdExist)) {
            DB::beginTransaction();
            // dd('OMG');
            try {
                $teacher =Student::where('id', $stdExist->id)->update($studentData);
                $teacherSchool = SchoolStudent::where(['student_id'=> $stdExist->id, 'school_id'=> $this->school_id])->update($schoolStudentData);
                DB::commit(); 
                ++$this->recordUpdated;
                Log::info("both exist and updated");
            } catch (Exception $e) {
                DB::rollBack();
                Log::error("import Student email: ".$data['email']." failed 3rd condtions in schoolId=".$this->school_id);
            }
        }else{
           Log::info("Not updated Student ".$data['email']." in schoolId=".$this->school_id); 
        }
    }


}
