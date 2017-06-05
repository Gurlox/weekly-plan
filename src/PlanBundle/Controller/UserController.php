<?php

namespace PlanBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use PlanBundle\Entity\User;
use PlanBundle\Form\UserType;

class UserController extends Controller
{
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        return $this->render('PlanBundle:user:register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function registerCommitAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $user = new User();
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
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
}
