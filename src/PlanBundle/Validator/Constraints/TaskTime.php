<?php
namespace PlanBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class TaskTime extends Constraint
{
    private $message = [
        'timeOccupied' => 'Czas zajęcia znajduje się w czasie trwania innego zajęcia',
        'timesDifferenceError' => 'Czas zakończenia zajęcia jest wcześniej niż czas jego rozpoczęcia'
    ];

    public function getMessage()
    {
        return $this->message;
    }

    public function getTargets()
    {
        return Constraint::CLASS_CONSTRAINT;
    }
}
