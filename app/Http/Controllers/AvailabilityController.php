<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Availability;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Vérifiez si l'utilisateur est un étudiant
        if ($user->isStudent()) {
            // Récupérez les disponibilités de l'étudiant connecté
            $availabilities = $user->student->availabilities;

            // Passez les disponibilités à la vue
            return view('pages.students.availability', compact('availabilities'));
        } else {
            // Si l'utilisateur n'est pas un étudiant, vous pouvez gérer cela en conséquence
            // Par exemple, redirigez-le vers une page d'erreur ou affichez un message approprié.
            return redirect()->route('agenda')->with('error', 'You do not have permission to view student availabilities.');
        }
    }

    public function indexByStudent(Student $student)
    {
        $availabilities = $student->availabilities;

        return view('pages.students.list_availabilities', compact('availabilities', 'student'));
    }

    public function store(Request $request)
    {
        /*$request->validate([
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'time_of_day' => 'required|in:AM,PM',
        ]);*/


        $user = $request->user();

        $selectedTime = $request->input('start_time');
        $selectedMinutes = $request->input('start_time_minute');
        $combinedStart = $selectedTime . ':' . $selectedMinutes;

        $selectedTimeEnd = $request->input('end_time');
        $selectedMinutesEnd = $request->input('end_time_minute');
        $combinedEnd = $selectedTimeEnd . ':' . $selectedMinutesEnd;

        $availability = new Availability([
            'student_id' => $user->person_id,
            'day_of_week' => $request->input('day_of_week'),
            'time_of_day' => $combinedStart . ' - ' . $combinedEnd,
            'start_time' => $combinedStart,
            'end_time' => $combinedEnd,
            'day_special' => $request->input('day_special'),
            'is_special' => $request->input('is_special') == 'true' ? true : false,
        ]);

        $availability->save();

        if($request->input('is_special')) {
            return response()->json(['success' => true, 'is_special' => $request->input('is_special')]);
        } else {
            return redirect()->route('student.availabilities')->with('success', 'Availability added successfully!');
        }
    }

    public function storeFromCoach(Request $request)
    {

        $student_id = $request->input('student_id');

        $selectedTime = $request->input('start_time');
        $selectedMinutes = $request->input('start_time_minute');
        $combinedStart = $selectedTime . ':' . $selectedMinutes;

        $selectedTimeEnd = $request->input('end_time');
        $selectedMinutesEnd = $request->input('end_time_minute');
        $combinedEnd = $selectedTimeEnd . ':' . $selectedMinutesEnd;

        $availability = new Availability([
            'student_id' => $student_id,
            'day_of_week' => $request->input('day_of_week'),
            'time_of_day' => $combinedStart . ' - ' . $combinedEnd,
            'start_time' => $combinedStart,
            'end_time' => $combinedEnd,
            'day_special' => $request->input('day_special'),
            'is_special' => $request->input('is_special') == 'true' ? true : false,
        ]);

        $availability->save();

        $userStudent = Student::find($student_id);
        $availabilities = $userStudent->availabilities;

        if($request->input('is_special')) {
            return response()->json(['success' => true, 'is_special' => $request->input('is_special')]);
        } else {
            return redirect()->route('students.availabilities', ['availabilities' => $availabilities, 'student' => $userStudent])->with('success', 'Availability deleted successfully');
        }

    }

    public function destroy(Availability $availability)
    {

    $user = Auth::user();
    $student = $availability->student;
    $availability->delete();

    if ($user->isStudent()) {
        return redirect()->route('student.availabilities')->with('success', 'Availability deleted successfully');
    } else {
        $availabilities = $student->availabilities;
        return redirect()->route('students.availabilities', ['availabilities' => $availabilities, 'student' => $student])->with('success', 'Availability deleted successfully');
    }
    }

}
