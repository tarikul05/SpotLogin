<?php

namespace App\Exports;

use App\Models\Student;

use App\Models\Teacher;
use App\Models\SchoolStudent;
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
            '*' => 15,
        ];
    }

    public function query()
    {
    	$schoolStudent = SchoolStudent::with(['student'])->where(['school_id' => $this->school_id]);
        return $schoolStudent;
    }

    /**
    * @var Invoice $teacher
    */
    public function map($schoolStd): array
    {
    	$username = isset($schoolStd->user) ? $schoolStd->user->username : null;
    	$nickname = isset($schoolStd->nickname) ? $schoolStd->nickname : null;
    	$bg_color_agenda = isset($schoolStd->bg_color_agenda) ? $schoolStd->bg_color_agenda : null;
    	$comment = isset($schoolStd->comment) ? $schoolStd->comment : null;
    	$gender = ($schoolStd->student->gender_id ==1) ? 'Male' : (($schoolStd->student->gender_id ==2) ? 'Female' : 'Not specified' );
    	
        return [
            $schoolStd->student->email,
            $username ,
            $schoolStd->student->lastname,
            $schoolStd->student->firstname,
            $nickname,
            $gender,
            $schoolStd->student->licence_js,
            $bg_color_agenda,
            $comment,
            $schoolStd->student->birth_date,
            $schoolStd->student->street,
            $schoolStd->student->street_number,
            $schoolStd->student->zip_code,
            $schoolStd->student->place,
            $schoolStd->student->phone,
            $schoolStd->student->mobile,
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
