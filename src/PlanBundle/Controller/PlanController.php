<?php

namespace PlanBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use PlanBundle\Form\PlanType;
use PlanBundle\Entity\Plan;
use PlanBundle\Entity\User;

class PlanController extends Controller
{
    public function redirectAction(Request $request)
    {
        if ($this->getUser() === null) {
            return $this->forward("PlanBundle:Security:login");
        } else {
            return $this->forward("PlanBundle:Plan:determineCurrentDay");
        }
    }

    private function buildPlanForm() {
        $newPlan = new Plan();
        $newPlan->setUser($this->getUser());
        return $this->createForm(PlanType::class, $newPlan);
    }

    private function dayNames()
    {
        return ['poniedzialek', 'wtorek', 'sroda', 'czwartek', 'piatek', 'sobota', 'niedziela'];
    }

    public function determineCurrentDayAction(Request $request)
    {
        $day = $this->dayNames()[date('w')-1];

        return $this->redirect($this->generateUrl('plan', ['day' => $day]));
    }

    public function planAction(Request $request, $day)
    {
        $day = array_search($day, $this->dayNames())+1;
        $user = $this->getUser();
        $form = $this->buildPlanForm();

        $em = $this->getDoctrine()->getManager();
        $tasks = [];
        $tasks = $em->getRepository('PlanBundle:Plan')
            ->findAllByDay($user->getId(), $day);

        return $this->render('PlanBundle:plan:plan.html.twig', [
            'form'  => $form->createView(),
            'tasks' => $tasks,
            'day'   => $day
        ]);
    }

    public function createTaskAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $form = $this->buildPlanForm();
            $form->handleRequest($request);

            if ($form->isValid()) {
                $newPlan = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($newPlan);
                $em->flush();

                return new JsonResponse([
                    'status' => 'success'
                ]);
            } else {
                $errors = [];

                foreach ($form->getErrors(true) as $error) {
                    $errors[] = $error->getMessage();
                }

                return new JsonResponse([
                    'status' => 'error',
                    'errors' => $errors
                ]);
            }
        } else {
            throw $this->createNotFoundException();
        }
    }

    public function deleteTaskAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $plan = $em->getRepository('PlanBundle:Plan')->findOneBy([
                'id'   => $request->request->get('id'),
                'user' => $this->getUser()->getId()
            ]);

            if ($plan == null) {
                return new JsonResponse([
                    'status'  => 'error',
                    'message' => 'Nie możesz modyfikować tego planu.'
                ]);
            } else {
                $em->remove($plan);
                $em->flush();
                return new JsonResponse([
                    'status' => 'success'
                ]);
            }
        } else {
            throw $this->createNotFoundException();
        }
    }
}
