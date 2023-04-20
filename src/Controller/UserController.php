<?php

namespace App\Controller;


use App\Entity\UserBu;
use App\Form\UserBuType;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(Request $request): Response
    {

        $user = new UserBu();
        // ...
        //UserType::class, 
        $form = $this->createForm(UserBuType::class, $user);

        // , [
        //     'action' => $this->generateUrl('app_user'),
        //     'method' => 'POST'
        // ]


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            die('qwfddf');
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            //            $task = $form->getData();

            // ... perform some action, such as saving the task to the database

            //            return $this->redirectToRoute('task_success');
        }



        dump($this->getUser());
        
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form
        ]);
    }
}
