<?php

namespace App\Http\Controllers\Admin;

use App\Application;
use App\ApplicationStatus;
use App\GroupStory;
use App\Student;
use App\Teacher;
use App\TeacherLimit;
use App\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminApplicationsController extends Controller
{
    protected $application;
    protected $teacher;
    protected $student;
    protected $teacherLimit;
    protected $groupStory;
    protected $applicationStatus;

    /**
     * AdminController constructor.
     * @param Application $application
     * @param Teacher $teacher
     * @param Student $student
     */
    public function __construct(Application $application, Teacher $teacher, Student $student,
                                TeacherLimit $teacherLimit, GroupStory $groupStory,
                                ApplicationStatus $applicationStatus)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->application = $application;
        $this->teacher = $teacher;
        $this->student = $student;
        $this->teacherLimit = $teacherLimit;
        $this->groupStory = $groupStory;
        $this->applicationStatus = $applicationStatus;
    }

    public function showTeacherApplications()
    {
        $teachers = $this->teacher->all();
        return view('admin.teacher-applications', ['teachers' => $teachers]);
    }

    /**
     * Показывает сколько студентов преподаваетли берут на практику и какие курсы, сортируя по году
     *
     * @param $year Выбранный год из списка
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showTeacherLimitsPage($year)
    {
        $teachers = $this->teacher->all();
        // Года для выпадающего списка
        $limitYears = $this->teacherLimit->select('year')->distinct()->orderBy('year')->get();
        // Проверка существования года в выпадающем списке для возможного добавление нового года в выпадющий список
        $yearExist = false;
        foreach($limitYears as $limitYear) {
            if ($limitYear->year == $year) $yearExist = true;
        }

        return view('admin.teacher-limits', ['teachers' => $teachers, 'year' => $year, 'limitYears' => $limitYears, 'yearExist' => $yearExist]);
    }

    public function setLimits(Request $request)
    {
        $teacherLimitsArray = $request->teacherLimitsArray;

        foreach($teacherLimitsArray as $teacherLimitArray) {
            $teacherLimitId = $teacherLimitArray['teacher_id'];
            $teacherLimitYear = $teacherLimitArray['year'];
            if ($this->teacherLimit->teacherLimitExist($teacherLimitId, $teacherLimitYear)) {
                $existTeacherLimit = $this->teacherLimit->where([
                    ['teacher_id', '=', $teacherLimitId],
                    ['year', '=', $teacherLimitYear]
                ])->first();
                $existTeacherLimit->update($teacherLimitArray);
            } else {
                $this->teacherLimit->create($teacherLimitArray);
            }
        }

        return "true";
    }

    /**
     * Выводим для каждого студента его заявку сортируя по группе и году
     *
     * @param $historyYear Год из таблицы group_stories, выбранный в выпадающем списке
     * @param null $groupStoryId Id группы из group_stories, выбранной в выпадающем списке
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showStudentLastApplications($historyYear, $groupStoryId = null)
    {
        // Получаем уникальные года из истории групп для вывода в выпадающий список
        $yearsGroupStories = $this->groupStory->select('year_history')->distinct()->orderBy('year_history')->get();
        // Если группы выбрали из выпадающего списка, передаем ее модель в шаблон
        if (isset($groupStoryId)) {
            $selectedGroupStory = $this->groupStory->where('id', '=', $groupStoryId)->first();
            $parameters['selectedGroupStory'] = $selectedGroupStory;

            // Получаем группу из таблицы groups связанную с группой из history_group
            $group = $selectedGroupStory->group()->first();
            $students = $group->students()->get();
            $parameters['students'] = $students;
        }

        $groupStoriesBySelectedYear = $this->groupStory->where('year_history', '=', $historyYear)->get();
        $parameters['groupStoriesBySelectedYear'] = $groupStoriesBySelectedYear;
        $parameters['historyYear'] = $historyYear;
        $parameters['yearsGroupStories'] = $yearsGroupStories;
        $parameters['teacherModel'] = $this->teacher;
        $parameters['applicationStatuses'] = $this->applicationStatus->all();

        return view('admin.student-applications', $parameters);
    }

    /**
     * Изменяет последнюю существующую заявку(студента, преподавателя, статус, время ответа при опредленном условии)
     * из данных запроса или создает новую, если заявок нет
     *
     * @param Request $request
     * @return string
     */
    public function changeOrCreateApplication(Request $request)
    {
        // Получение массива с данными о заявке из ajax запроса
        $applictionDataArray = $request->applictionDataArray;

        $lastApplication = $this->application->where([
            ['year', '=', $applictionDataArray['year']],
            ['student_id', '=', $applictionDataArray['studentId']]
        ])->get()->last();

        if (isset($lastApplication)) {
            $parameters = [
                'teacher_id' => $applictionDataArray['teacherId'],
                'status_id' => $applictionDataArray['statusId'],
                'reply_datetime' => date("Y-m-d H:i:s")
            ];

            // Если статус существующей заявки - ожидание, значит даты ответа нет
            if ($applictionDataArray['statusId'] == 1) {
                $parameters['reply_datetime'] = null;
            }

            $lastApplication->update($parameters);
        } else {
            $parameters = [
                'year' => $applictionDataArray['year'],
                'student_id' => $applictionDataArray['studentId'],
                'teacher_id' => $applictionDataArray['teacherId'],
                'type_id' => 1,
                'status_id' => $applictionDataArray['statusId'],
                'reply_datetime' => date("Y-m-d H:i:s")
            ];

            // Если статус существующей заявки - ожидание, значит даты ответа нет
            if ($applictionDataArray['statusId'] == 1) {
                $parameters['reply_datetime'] = null;
            }

            $this->application->create($parameters);
        }

        return "true" ;
    }
}