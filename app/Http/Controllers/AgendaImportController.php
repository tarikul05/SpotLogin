<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AgendaImport;
use App\Models\EventCategory;
use Illuminate\Support\Facades\Auth;
use App\Models\SchoolStudent;
use App\Models\Location;
use App\Http\Controllers\LessonsController;
use App\Models\AgendaImport as AgendaImportModel;

class AgendaImportController extends Controller
{
    public function import(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->selectedSchoolId();

        /*$request->validate([
            'csvFile' => 'required|mimes:xlsx,xls',
        ]);*/

        $file = $request->file('csvFile');
        $import = new AgendaImport();
        Excel::import($import, $file);

        $data = $import->getData();

        //dd($data);
        if (count($data) > 0) {
            $counter = count($data);
        } else {
            $counter = 0;
        }
        
        if ($user->isSuperAdmin()) {
            $school = School::active()->find($schoolId);
            if (empty($school)) {
                return redirect()->route('schools')->with('error', __('School is not selected'));
            }
            $schoolId = $school->id;
        }else {
            $schoolId = $user->selectedSchoolId();
        }

        $categories = EventCategory::active()->where('school_id', $schoolId)->get();
        
        $students = SchoolStudent::where(['school_id' => $schoolId, 'is_active' => 1])->with('student')->get();

            //dd($data);

            foreach ($data as $row) {
                AgendaImportModel::create([
                    'teacher_id' => $user->person_id,
                    'coach' => $row['professeur'],
                    'duration' => $row['duration_minutes'],
                    'title' => $row['cours'],
                    'start_time' => $row['heure_de_dpart'],
                    'students_names' => $row['nom_de_student_name'],
                    'end_time' => $row['heure_de_fin'],
                    'date' => $row['date'],
                    'count_students' => $row['nombre_of_studiants'] ?? null,
                    'imported' => false,
                ]);
            }

            //$data = AgendaImportModel::where('imported', false).where('teacher_id', $user->person_id)->get();
            //return view('pages.agenda.show', compact('data', 'counter', 'categories', 'students'));

            return redirect()->route('import.getLessons')->with('success', __('Successfully Imported'));

    }

    public function deleteImport()
    {
        $user = auth()->user();
        $schoolId = $user->selectedSchoolId();
        AgendaImportModel::where('teacher_id', $user->person_id)->delete();

        return redirect()->route('agenda')->with('success', __('Import Successfully Deleted !'));
    }

    public function getAgendaImportModel()
    {
        $user = Auth::user();

        $schoolId = $user->selectedSchoolId();

        $data = AgendaImportModel::where('teacher_id', $user->person_id)->where('imported', false)->get();
    
        if (count($data) > 0) {
            $counter = count($data);
        } else {
            $counter = 0;
        }

        $categories = EventCategory::active()->where('school_id', $schoolId)->get();

        $locations = Location::active()->where('school_id', $schoolId)->get();

        $students = SchoolStudent::where(['school_id' => $schoolId, 'is_active' => 1])->with('student')->get();

        return view('pages.agenda.imported', compact('data', 'counter', 'locations', 'categories', 'students'));
    }


    public function addLesson(Request $request)
    {
        $lessonData = $request->input('lesson_data');
        $students = $request->input('students');
        $category = $request->input('category');
        $date = $request->input('date');
        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');
        $location = $request->input('location');

        $idDelete = $lessonData['id'];
        $durationString = $lessonData['duration'];
        $words = explode(' ', $durationString);


        $startDateTime = \DateTime::createFromFormat('H:i', $start_time);
        $endDateTime = \DateTime::createFromFormat('H:i', $end_time);

        if ($startDateTime && $endDateTime) {
            $interval = $startDateTime->diff($endDateTime);
            $duration = $interval->h * 60 + $interval->i;
        } else {
            $duration = null;
        }
  
        $finalLessonData = [
            'date' => $date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'students' => $students,
            'description' => $lessonData['title'],
            'duration' => $duration,
            'professor' => $lessonData['coach'],
            'category' => $category 
        ];

    
        // Debugging purposes
        //dd($finalLessonData);
        $lessonController = new LessonsController();
        $response = $lessonController->addImportedLesson($finalLessonData, $students, $category, $location, $schoolId = null);

        if($response) {
            AgendaImportModel::where('id', $idDelete)->delete();
        }

        // Return the final lesson data as a JSON response for now
        return response()->json($response);
    }
}
