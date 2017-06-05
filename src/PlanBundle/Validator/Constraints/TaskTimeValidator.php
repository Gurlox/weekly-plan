<?php
namespace PlanBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;

class TaskTimeValidator extends ConstraintValidator
{
    private $em;
    private $tokenStorage;

    public function __construct(EntityManager $em, $tokenStorage) {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    public function validate($protocol, Constraint $constraint)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        $tasks = [];
        $tasks = $this->em->getRepository('PlanBundle:Plan')
            ->findAllByDay($user->getId(), $protocol->getDay());

        $startTime = $protocol->getStartTime();
        $endTime = $protocol->getEndTime();
        foreach ($tasks as $task) {
            if (($startTime >= $task['startTime'] && $startTime <= $task['endTime']) || ($endTime >= $task['startTime'] && $endTime <= $task['endTime'])) {
                $this->context->buildViolation($constraint->getMessage()['timeOccupied'])
                    ->addViolation();
                break;
            }
        }

        if ($startTime >= $endTime) {
            $this->context->buildViolation($constraint->getMessage()['timesDifferenceError'])
                ->addViolation();
        }
    }
}
