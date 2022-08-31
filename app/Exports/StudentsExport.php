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


class StudentsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    use Exportable;

	private $school_id;

    public function __construct(int $school_id) 
    {
        $this->school_id = $school_id;
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
			'Q' => 15,
			'R' => 15,
			'S' => 15,
			'T' => 15,
			'U' => 15,
			'V' => 15,
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
            $schoolStd->student->lastname,
            $schoolStd->student->firstname,
            $nickname,
            $gender,
            $schoolStd->student->licence_usp,
            $comment,
            $schoolStd->student->birth_date,
            $schoolStd->student->street,
            $schoolStd->student->street_number,
            $schoolStd->student->zip_code,
            $schoolStd->student->place,
            $schoolStd->student->billing_street,
            $schoolStd->student->billing_street_number,
            $schoolStd->student->billing_zip_code,
            $schoolStd->student->billing_place,
            $schoolStd->student->father_phone,
            $schoolStd->student->father_email,
            $schoolStd->student->mother_phone,
            $schoolStd->student->mother_email,
            $schoolStd->student->mobile,
            $schoolStd->student->email2,
        ];
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


    public function styles(Worksheet $sheet)
    {
        return [
            // Styling an entire column.
            'A1'  => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'ff1800']] ],
            'B1'  => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'ff1800']] ],
            'C1'  => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'ff1800']] ],
            'D1'  => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'ff1800']] ],
        ];
    }
}
