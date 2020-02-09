<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id', 'teacher_id', 'type_id', 'status_id'
    ];

    /**
     * Проверяет существование заявки
     *
     * @param $studentId
     * @param $applicationTypeId
     * @return mixed
     */
    public function applicationExists($studentId, $applicationTypeId)
    {
        $application = $this->where([
            ['student_id', '=', $studentId],
            ['type_id', '=', $applicationTypeId]
        ])->first();

        if (!$application) {
            return false;
        }

        return true;
    }
}