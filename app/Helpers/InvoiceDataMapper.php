<?php

namespace App\Helpers;
use Carbon\Carbon;
use App\Models\SchoolTeacher;
use App\Models\Teacher;
use App\Models\SchoolStudent;
use App\Models\Student;


/**
 * InvoiceData mapper class.
 *
 * Map the `$InvoiceData` array.
 */
class InvoiceDataMapper
{
    /**
     * Transaction date.
     */
    public string $date;

    /**
     * Class constructor.
     *
     */
    public function __construct()
    {
        
    }

    /**
     * Get request data and set data for save
     *
     * @return array column mappers
     */
    public function setInvoiceData($data,$school,$invoice_type,$user,$invoice_type_id=1): array
    {

        $p_invoice_id=trim($data['p_invoice_id']);
        $p_discount_perc=trim($data['p_discount_perc']);
        if ($p_discount_perc=''){
            $p_discount_perc=0;
        }
        $p_teacher_id = $p_person_id = trim($data['p_person_id']);
        $schoolId = $p_school_id = trim($data['school_id']);
        $p_billing_period_start_date = trim($data['p_billing_period_start_date']);
        $p_billing_period_end_date = trim($data['p_billing_period_end_date']);
        $dateS = $p_billing_period_start_date = date('Y-m-d', strtotime(str_replace('.', '-', $p_billing_period_start_date)));
        $dateEnd = $p_billing_period_end_date = date('Y-m-d', strtotime(str_replace('.', '-', $p_billing_period_end_date)));


        $invoiceData =[
            'school_id' => $schoolId,
            'invoice_type' => $invoice_type_id,
            'invoice_status' => 1,
            'invoice_name' => 'Invoice '.Carbon::now()->format('F').' '.Carbon::now()->year,
            'period_starts' => $dateS,
            'period_ends' => $dateEnd,
            'date_invoice' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
        if ($p_invoice_id != '') {
            $invoiceData['invoice_no'] = $p_invoice_id;
        }
        


        if ($invoice_type == 'T') {
            $professors = SchoolTeacher::active()->where('school_id',$schoolId);
            $professors->where('teacher_id',$user->person_id);
            $professors = $professors->first();
            $teacher = Teacher::find($professors->id);

            $invoiceData['seller_id'] = $teacher->id;
            $invoiceData['seller_name'] = $teacher->firstname.' '.$teacher->lastname;
            $invoiceData['seller_street'] = $teacher->street;
            $invoiceData['seller_street_number'] = $teacher->street_number;
            $invoiceData['seller_street2'] = $teacher->street2;
            $invoiceData['seller_zip_code'] = $teacher->zip_code;
            $invoiceData['seller_place'] = $teacher->place;
            $invoiceData['seller_country_code'] = $teacher->country_code;
            $invoiceData['seller_phone'] = $teacher->phone;
            $invoiceData['seller_mobile'] = $teacher->mobile;
            $invoiceData['seller_email'] = $teacher->email;
            $invoiceData['seller_gender_id'] = $teacher->gender_id;
            $invoiceData['seller_lastname'] = $teacher->lastname;
            $invoiceData['seller_firstname'] = $teacher->firstname;
            
            $invoiceData['payment_bank_iban'] = $teacher->bank_iban;
            $invoiceData['payment_bank_account'] = $teacher->bank_account;
            $invoiceData['payment_bank_swift'] = $teacher->bank_swift;
            $invoiceData['payment_bank_name'] = $teacher->bank_name;
            $invoiceData['payment_bank_address'] = $teacher->bank_address;
            $invoiceData['payment_bank_zipcode'] = $teacher->bank_zipcode;
            $invoiceData['payment_bank_place'] = $teacher->bank_place;
            $invoiceData['payment_bank_country_code'] = $teacher->bank_country_code;

        } else {
            $invoiceData['seller_id'] = $schoolId;
            $invoiceData['seller_name'] = $school->school_name;
            $invoiceData['seller_street'] = $school->street;
            $invoiceData['seller_street_number'] = $school->street_number;
            $invoiceData['seller_street2'] = $school->street2;
            $invoiceData['seller_zip_code'] = $school->zip_code;
            $invoiceData['seller_place'] = $school->place;
            $invoiceData['seller_country_code'] = $school->country_code;
            $invoiceData['seller_phone'] = $school->phone;
            $invoiceData['seller_mobile'] = $school->mobile;
            $invoiceData['seller_email'] = $school->email;
            $invoiceData['seller_gender_id'] = $school->contact_gender_id;
            $invoiceData['seller_lastname'] = $school->contact_lastname;
            $invoiceData['seller_firstname'] = $school->contact_firstname;
            $invoiceData['payment_bank_iban'] = $school->bank_iban;
        
            $invoiceData['payment_bank_account_name'] = $school->bank_account_holder;
            $invoiceData['payment_bank_account'] = $school->bank_account;
            $invoiceData['payment_bank_swift'] = $school->bank_swift;
            $invoiceData['payment_bank_name'] = $school->bank_name;
            $invoiceData['payment_bank_address'] = $school->bank_address;
            $invoiceData['payment_bank_zipcode'] = $school->bank_zipcode;
            $invoiceData['payment_bank_place'] = $school->bank_place;
            $invoiceData['payment_bank_country_code'] = $school->bank_country_code;
        }


        if ($invoice_type_id == 2) {
            $invoiceData =[
                'client_id' => $schoolId,
                'seller_id' => $p_person_id,
                'discount_percent_1' => $p_discount_perc,
            ];
            if (!empty($p_teacher_id)) {
                $Steacher = SchoolTeacher::active()->where('school_id',$schoolId);
                $Steacher->where('teacher_id',$p_teacher_id);
                $teacherSchool = $Steacher->first();
                $teacher = Teacher::find($teacherSchool->teacher_id);
                $invoiceData['client_id'] = $teacher->id;
                $invoiceData['client_name'] = $teacher->firstname.'N '.$teacher->lastname;
                $invoiceData['client_street'] = $teacher->street;
                $invoiceData['client_street_number'] = $teacher->street_number;
                $invoiceData['client_street2'] = $teacher->street2;
                $invoiceData['client_zip_code'] = $teacher->zip_code;
                $invoiceData['client_place'] = $teacher->place;
                $invoiceData['client_country_code'] = $teacher->country_code;
                $invoiceData['client_gender_id'] = $teacher->gender_id;
                $invoiceData['client_lastname'] = $teacher->lastname;
                $invoiceData['client_firstname'] = $teacher->firstname;
            }
        } else {
            if (!empty($p_student_id)) {
                $Sstudent = SchoolStudent::active()->where('school_id',$schoolId);
                $Sstudent->where('student_id',$p_student_id);
                $studentSchool = $Sstudent->first();
                $student = Student::find($studentSchool->student_id);
                $invoiceData['client_id'] = $student->id;
                $invoiceData['client_name'] = $student->firstname.'N '.$student->lastname;
                $invoiceData['client_street'] = $student->street;
                $invoiceData['client_street_number'] = $student->street_number;
                $invoiceData['client_street2'] = $student->street2;
                $invoiceData['client_zip_code'] = $student->zip_code;
                $invoiceData['client_place'] = $student->place;
                $invoiceData['client_country_code'] = $student->country_code;
                $invoiceData['client_gender_id'] = $student->gender_id;
                $invoiceData['client_lastname'] = $student->lastname;
                $invoiceData['client_firstname'] = $student->firstname;
            }
        }


        $invoiceData['discount_percent_1'] = $school->discount_percent_1;
        $invoiceData['discount_percent_2'] = $school->discount_percent_2;
        $invoiceData['discount_percent_3'] = $school->discount_percent_3;
        $invoiceData['discount_percent_4'] = $school->discount_percent_4;
        $invoiceData['discount_percent_5'] = $school->discount_percent_5;
        $invoiceData['discount_percent_6'] = $school->discount_percent_6;
        
        $invoiceData['category_invoiced_type'] = $invoice_type;
        
        $invoiceData['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
        

        return $invoiceData;
    }
}