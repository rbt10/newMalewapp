<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Services\NavProvinces;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
private NavProvinces $navProvinces;


    public function __construct( NavProvinces $navProvinces)
    {
        $this->navProvinces = $navProvinces;
    }
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $notification = null;
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            $adress = $data['email'];
            $content = $data['Message'];
            $email = (new Email())
                ->from($adress)
                ->to('ronytula@gmail.com')
                ->subject('Demande de contact!')
                ->text($content);

            $mailer->send($email);
            $this->addFlash(
                type: 'success',
                message:'Votre message a été envoyé avec succès'
            );
        }

        return $this->render('contact/index.html.twig',[
                'form'=>$form->createView(),
                'provinces' => $this->navProvinces->provinces()
            ]
        );
    }
    #[Route('/apropos', name: 'app_apropos')]
    public function apropos(): Response
    {
        return $this->render('contact/apropos.html.twig',[
                'provinces' => $this->navProvinces->provinces()
            ]
        );
    }
    #[Route('/qui-sommes-nous', name: 'app_qui_sommes_nous')]
    public function quiSommesNous(): Response
    {
        return $this->render('contact/qui-sommes-nous.html.twig',[
                'provinces' => $this->navProvinces->provinces()
            ]
        );
    }
}
