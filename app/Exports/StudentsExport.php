<?php

namespace App\Exports;

use App\Models\Student;

use App\Models\Teacher;
use App\Models\SchoolTeacher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

// class TeachersExport implements FromCollection
class StudentsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    use Exportable;

	private $school_id;

    public function __construct(int $school_id) 
    {
        $this->school_id = $school_id;
    }

	public function headings(): array
    {
    	$headerRow = [
            'Email',
            'Family Name',
            'Firstname',
            'Nickname',
            'Gender',
            'Licence',
            'Comment',
            'Billing Method',
            'Birth date',
            'Street',
            'Street No',
            'Postal Code',
            'City',
            'Billing Street',
            'Billing street No',
            'Billing Postal code',
            'Billing city',
            "Father's Phone",
            "Father's email",
            "Mother's phone",
            "Mother's email",
            "Student's Phone",
            "Student's 2nd Email",
        ];
        return $headerRow;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 15,
			'C' => 15,
			'D' => 15,
			'E' => 15,
			'F' => 15,
			'G' => 15,
			'H' => 15,
			'I' => 15,
			'J' => 15,
			'K' => 15,
			'L' => 15,
			'M' => 15,
			'N' => 15,
			'O' => 15,
			'P' => 15,        
        ];
    }

    public function query()
    {
    	$schoolTeachers = SchoolTeacher::with(['teacher'])->where(['school_id' => $this->school_id])->whereIn('role_type',['teachers_all', 'teachers_medium','teachers_minimum']);
        return $schoolTeachers;
    }

    /**
    * @var Invoice $teacher
    */
    public function map($schTeacher): array
    {
    	$username = isset($schTeacher->user) ? $schTeacher->user->username : null;
    	$nickname = isset($schTeacher->nickname) ? $schTeacher->nickname : null;
    	$bg_color_agenda = isset($schTeacher->bg_color_agenda) ? $schTeacher->bg_color_agenda : null;
    	$comment = isset($schTeacher->comment) ? $schTeacher->comment : null;
    	$gender = ($schTeacher->teacher->gender_id ==1) ? 'Male' : (($schTeacher->teacher->gender_id ==2) ? 'Female' : 'Not specified' );
    	
        return [
            $schTeacher->teacher->email,
            $username ,
            $schTeacher->teacher->lastname,
            $schTeacher->teacher->firstname,
            $nickname,
            $gender,
            $schTeacher->teacher->licence_js,
            $bg_color_agenda,
            $comment,
            $schTeacher->teacher->birth_date,
            $schTeacher->teacher->street,
            $schTeacher->teacher->street_number,
            $schTeacher->teacher->zip_code,
            $schTeacher->teacher->place,
            $schTeacher->teacher->phone,
            $schTeacher->teacher->mobile,
        ];
    }


    public function styles(Worksheet $sheet)
    {
        return [
            // Styling an entire column.
            'A1'  => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'ff1800']] ],
            'C1'  => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'ff1800']] ],
            'D1'  => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'ff1800']] ],
            'E1'  => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'ff1800']] ],
        ];
    }
}
