<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AbonneRepository;

class SendController extends AbstractController
{
    /**
     * @Route("/send", name="send")
     */
    public function index( \Swift_Mailer $mailer, AbonneRepository $abonnerepository ): Response
    {


       $abonnes =  $abonnerepository -> findAll();
       $listemail = [];
       foreach( $abonnes as $abonne ){
        $listemail []= $abonne->getEmail();
       }
        
        $this->sendEmail($listemail , $mailer);

        return $this->render('send/index.html.twig');
    //return $this->render('emails/registration.html.twig',['name'=> $name]);
    }

/**
* Permet d'envoyer des emails
*/
public function sendEmail($listemail, \Swift_Mailer $mailer)
{
    $mail = new \Swift_Message();
    $mail
        -> setSubject('email newsletter')
        -> setFrom('test@gmail.com')
        -> setBcc($listemail)
        -> setBody(
            $this -> renderView('emails/registration.html.twig', [
            'name' => $listemail
        ]), 'text/html'
        )
    ;
 
    if($mailer -> send($mail))
    {
        return true;
    }
    else
    {
        return false;
    }
}

}
