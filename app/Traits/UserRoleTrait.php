<?php

namespace App\Traits;

trait UserRoleTrait
{
    /**
     * Get user role and invoice type
     *
     * @param object $user
     * @param object $school
     *
     * @return array result
     */
    public function getUserRoleInvoiceType($user,$school = null)
    {
        $user_role = 'superadmin';
        if ($user->person_type == 'App\Models\Student') {
            $user_role = 'student';
        }
        if ($user->person_type == 'App\Models\Teacher') {
            $user_role = 'teacher';
        }
        if ($user->isSchoolAdmin() || $user->isTeacherAdmin()) {
            $user_role = 'admin_teacher';
            if ($user->isTeacherAdmin()) {
                $user_role = 'coach_user';
            }
        }
        if ($user->isTeacherAll()) {
            $user_role = 'teacher_all';
        }
        if ($user->isTeacherMedium() || $user->isTeacherMinimum() || $user_role == 'teacher') {
            $user_role = 'teacher';
        }
        //get invoice type
        if ($user_role == 'admin_teacher' || $user_role == 'coach_user') {
            $invoice_type = 'S';
        } else if ((!empty($school) && $school->school_type == 'C') || $user_role == 'teacher_all') {
            $invoice_type = 'T';
        } else if ($user_role == 'teacher') {
            $invoice_type = 'T';
        } else {
            $invoice_type = 'S';
        }
        $result =[$user_role,$invoice_type];
        return $result;
    }
}
