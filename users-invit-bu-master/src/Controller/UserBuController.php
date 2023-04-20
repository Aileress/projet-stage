<?php

namespace App\Controller;

use App\Entity\UserBu;
use App\Form\UserBuType;
use App\Repository\UserBuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Ldap\Ldap;

#[Route('/user/bu')]
class UserBuController extends AbstractController
{


    #[Route('/sendMail/{emailAdress}', name: 'app_user_bu_send_mail', methods: ['POST'])]
    public function sendMail($emailAdress, UserBuRepository $userBuRepository, MailerInterface $mailer): Response
    {

        

        $email = (new Email())
        ->from('Nicolas.DELPEUCH@uca.fr')
        ->to($emailAdress)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!') 
            ->html('<p>See Twig integration for better HTML integration! </p>'); 

        $test = $mailer->send($email);
        
        
        return $this->render('user_bu/index.html.twig', [
            'user_bus' => $userBuRepository->findAll(),
        ]);
        
    }


    #[Route('/', name: 'app_user_bu_index', methods: ['GET'])]
    public function index(UserBuRepository $userBuRepository, MailerInterface $mailer): Response
    {
       
        
        $ldap = Ldap::create('ext_ldap', ['connection_string' => 'ldaps://ldap.uca.fr']);
            $ldap->bind();



            $infoCompteLdap = $ldap->query("dc=uca,dc=fr", "(&(cn=bu-invit-lecteur))")->execute()->toArray();

            $members = $infoCompteLdap[0]->getAttributes()['member'];


            dump($this->getUser()->getUserIdentifier());
            $listRoles = [];

            //foreach de chaque membres dans ce que tu recup
            //on compare avec la regex ci-dessous et si ok alors on attribut le droit qui va bien
            foreach($members as $member){
        

                dump($member);
                if (preg_match('/uid=(.*?),/',$member,  $match)) {
                    dump($match[1]);
                }
                
            }

       
        
        return $this->render('user_bu/index.html.twig', [
            'user_bus' => $userBuRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_bu_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserBuRepository $userBuRepository): Response
    {
        $userBu = new UserBu();
        $form = $this->createForm(UserBuType::class, $userBu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userBu->setListeService($form->get('listeServices')->getData());
            $userBuRepository->save($userBu, true);

            return $this->redirectToRoute('app_user_bu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_bu/new.html.twig', [
            'user_bu' => $userBu,
            'form' => $form,
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): void
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    #[Route('/{id}', name: 'app_user_bu_show', methods: ['GET'])]
    public function show(UserBu $userBu): Response
    {
        return $this->render('user_bu/show.html.twig', [
            'user_bu' => $userBu,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_bu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserBu $userBu, UserBuRepository $userBuRepository): Response
    {

       // dd($request);
       // die;
        $form = $this->createForm(UserBuType::class, $userBu);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userBu->setListeService($form->get('listeServices')->getData());
            $userBuRepository->save($userBu, true);
                
            

            return $this->redirectToRoute('app_user_bu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_bu/edit.html.twig', [
            'user_bu' => $userBu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_bu_delete', methods: ['POST'])]
    public function delete(Request $request, UserBu $userBu, UserBuRepository $userBuRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userBu->getId(), $request->request->get('_token'))) {
            $userBuRepository->remove($userBu, true);
        }

        return $this->redirectToRoute('app_user_bu_index', [], Response::HTTP_SEE_OTHER);
    }


}