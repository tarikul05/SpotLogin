<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use DB;
use App\Models\CalendarSetting;
use App\Models\EventCategory;
use App\Models\LessonPrice;
use App\Models\School;
use App\Models\LessonPriceTeacher;
use App\Models\Teacher;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use DateTimeZone;
use App\Models\SchoolTeacher;
use App\Models\Location;
use App\Models\InvoicesTaxes;
use App\Models\Level;
use App\Models\Province;
use Illuminate\Support\Facades\Session;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        // Récupérez l'utilisateur connecté
        $user = auth()->user();

        $calendarSettings = $user->calendarSetting ?? new CalendarSetting;

        $user = Auth::user();
        $schoolId = $request->route('school');
        $teacherId = $request->route('teacher');

        $teacher = Teacher::find($user->person_id);
        $schoolId = $user->selectedSchoolId();
        $schoolName = $user->selectedSchoolName();


        $relationalData = SchoolTeacher::where([
            ['teacher_id',$teacher->id],
            ['school_id',$schoolId]
        ])->first();
        $lanCode = 'en';
        if (Session::has('locale')) {
            $lanCode = Session::get('locale');
        }


        if($user->isSchoolAdmin() || $user->isTeacherAdmin()){
            $eventCategory = EventCategory::schoolInvoiced()->where('school_id',$schoolId)->get();
        }else{
            $eventCategory = EventCategory::teacherInvoiced()->where('school_id',$schoolId)->get();
        }

        $lessonPrices = LessonPrice::active()->orderBy('divider', 'asc')->get();
        $lessonPriceTeachers = LessonPriceTeacher::active()
                              ->where(['teacher_id' => $teacher->id])
                              ->whereIn('event_category_id',$eventCategory->pluck('id'))
                              ->get();
        $ltprice =[];
        foreach ($lessonPriceTeachers as $lkey => $lpt) {
          $ltprice[$lpt->event_category_id][$lpt->lesson_price_student] = $lpt->toArray();
        }
        // dd($lessionPriceTeacher);

        $countries = Country::active()->get();
        $genders = config('global.gender');

        $eventCat = EventCategory::active()->where('school_id', $schoolId)->get();
        $eventLastCatId = DB::table('event_categories')->orderBy('id','desc')->first();
        $locations = Location::active()->where('school_id', $schoolId)->get();
        $eventLastLocaId = DB::table('locations')->orderBy('id','desc')->first();
        $levels = Level::active()->where('school_id', $schoolId)->get();
        $eventLastLevelId = DB::table('levels')->orderBy('id','desc')->first();
        $school = School::find($schoolId);
        $InvoicesTaxData = InvoicesTaxes::active()->where(['invoice_id'=> null, 'created_by' => $user->id])->get();

        $timezone = $school->timezone;
        $europeanTimezones = DateTimeZone::listIdentifiers(DateTimeZone::EUROPE);
        $isInEurope = in_array($timezone, $europeanTimezones);

        // dd($relationalData);
        return view('pages.calendar.settings')->with(compact('levels',
        'eventLastLevelId',
        'locations',
        'school',
        'eventLastLocaId',
        'eventCat',
        'InvoicesTaxData',
        'eventLastCatId','teacher','relationalData','countries','genders','schoolId','schoolName','eventCategory','lessonPrices','ltprice', 'isInEurope', 'calendarSettings'));
    }

    public function store(Request $request)
    {

        // Récupérez l'utilisateur connecté
        $user = auth()->user();

        // Récupérez les paramètres de calendrier de l'utilisateur s'ils existent, sinon, créez un nouvel enregistrement
        $calendarSettings = $user->calendarSetting ?? new CalendarSetting;

        // Remplissez les propriétés avec les données du formulaire
        $calendarSettings->timezone = $request['timezone'];
        $calendarSettings->min_time = $request['min_time'];
        $calendarSettings->max_time = $request['max_time'];

        // Enregistrez les paramètres de calendrier
        $user->calendarSetting()->save($calendarSettings);

        // Redirigez l'utilisateur vers la page de paramètres avec un message de succès
        return redirect()->route('calendar.settings')->with('success', 'Paramètres de calendrier enregistrés avec succès.');
    }
}
