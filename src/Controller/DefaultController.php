<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
use App\Repository\CategRepository;
use App\Repository\PersonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        $tab["message"] ="Home";
        $jean = "Jean DUJARDIN";
        $fruits =['pomme','poire','kiwi'];

        //return $this->json($tab);
        return $this->render('default/index.html.twig',[
            "prenom"=> $jean,
            "age" =>42,
            "fruits" => $fruits,
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        $tab["message"] ="Contact";
        return $this->json($tab);
    }

    /**
     * @Route("/about-us", name="about_us")
     */
    public function about(PersonneRepository $repo): Response
    {
        $tab = $repo->findAll();

        return $this->render('default/about.html.twig',[
            'tableau'=>$tab]);
    }

    /**
     * @Route("/detail/{id}", name="detail")
     */
    public function detail(Personne $personne): Response
    {
        dd($personne);
        //$tab = $repo->findAll();

        return $this->render('default/about.html.twig',[
            'tableau'=>$tab]);
    }

    /**
     * @Route("/enlever/{id}", name="personne_enlever")
     */
    public function enlever(Personne $personne,EntityManagerInterface $em): Response
    {
       // $em = $this->getDoctrine()->getManager();
        $em->remove($personne);
        $em->flush();
        return $this->redirectToRoute('about_us');
    }

    /**
     * @Route("/ajouter-dure/", name="personne_ajouter_dure")
     */
    public function ajouterDure(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $personne = new Personne();
        $personne->setNom('JOLIE');
        $personne->setPrenom('Angelina');
        $em->persist($personne);
        $em->flush();
       // dd($personne);
        return $this->redirectToRoute('about_us');
    }

    /**
     * @Route("/modif-dure/{id}", name="personne_modif_dure")
     */
    public function modifDure(Personne $personne): Response
    {
        $em = $this->getDoctrine()->getManager();
        $personne->setPrenom('Jean');
        $em->flush();
        // dd($personne);
        return $this->redirectToRoute('about_us');
    }
//----------------------------------------------------------------
    /**
     * @Route("/ajouter/", name="personne_ajouter")
     */
    public function ajouter(Request $request,CategRepository $repo): Response
    {
        $categ = $repo->find(1);
        $personne = new Personne();
       // $personne->setCateg($categ);
        // associe obj personne au Form.
        $formPersonne = $this->createForm(PersonneType::class,$personne);
        // hydraté $personne en fct du formulaire
        $formPersonne->handleRequest($request);
        // si le form est validé.
        if ($formPersonne->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($personne);
            $em->flush();
            // je redirige
            return $this->redirectToRoute('about_us');
        }
        return $this->render('default/ajouter.html.twig',
            ['formPersonne'=> $formPersonne->createView()]);
    }
//----------------------------------------------------------------
    /**
     * @Route("/modifier/{id}", name="personne_modifier")
     */
    public function modifier(Personne $personne,Request $request): Response
    {
       // $personne = new Personne();
        // associe obj personne au Form.
        $formPersonne = $this->createForm(PersonneType::class,$personne);
        // hydraté $personne en fct du formulaire
        $formPersonne->handleRequest($request);
        // si le form est validé.
        if ($formPersonne->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            //$em->persist($personne);
            $em->flush();
            // je redirige
            return $this->redirectToRoute('about_us');
        }
        return $this->render('default/modifier.html.twig',
            ['formPersonne'=> $formPersonne->createView()]);
    }

}
