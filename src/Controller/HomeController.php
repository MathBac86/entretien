<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\ContactFormType;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;

class HomeController extends AbstractController
{
    private $slugger;
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * @Route("/", name="home")
     */
    public function home(Request $request, EntityManagerInterface $emi, SendEmailService $sendEmail ): Response
    {
        $user = new Users();
        $form = $this->createForm(ContactFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setDateMailSend(new \DateTimeImmutable('now'));
            $file = $form->get('File')->getData();
            /*var_dump($file); exit;*/

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('file_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $user->setFile($newFilename);
            }

            $emi->persist($user);
            $emi->flush();

            $sendEmail->send([
                'recipient_email' => $user->getEmail(),
                'subject' => 'Envoi mail pour entretien',
                'html_template' => 'home/sendEmail.html.twig',
                'file' => $user->getFile(),
                'context' => [
                    'name' => $user->getName(),
                    'firstname' => $user->getFirstname()
                ]
            ]);

            $this->addFlash('success', "Votre compte utilisateur a bien été créé et un mail a été envoyé.");

            return $this->redirectToRoute('home');
        }        

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'form' => $form->createView()
        ]);
    }
}
