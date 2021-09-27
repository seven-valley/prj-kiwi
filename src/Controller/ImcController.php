<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImcController extends AbstractController
{
    /**
     * @Route("/imc/", name="imc" ,methods={"POST"})
     */
    public function index(Request $req): Response
    {
        $json =$req->getContent();

        $obj = json_decode($json);

        $poids = $obj->poids;
        $taille = $obj->taille;
        $imc = $poids / ($taille*$taille);
        if ($imc< 18.5){
            $tab["tranche"]="Maigreure";
        }else if ($imc < 25 ){
            $tab["tranche"]="Normal";
        }else{
            $tab["tranche"]="Surpoids";
        }
        $tab["imc"]=$imc;
        return $this->json($tab);
    }

    /**
     * @Route("/calcul-imc/", name="calcul_imc" ,methods={"GET"})
     */
    public function calculImc(): Response
    {
        return $this->render('imc/index.html.twig');
    }

    /**
     * @Route("/test-axios/", name="test-axios" ,methods={"POST"})
     */
    public function testAxios(Request $req): Response
    {
        $json =$req->getContent();

        $obj = json_decode($json);
        //print_r($obj);
        //die();
        $tab["message"]= " Test ok";
        return $this->json($tab);
    }
}
