<?php

namespace App\Controller;

use App\Entity\DataToConvert;
use App\Form\DataToConvertType;
use App\Manager\PdfManager;
use App\Repository\DataToConvertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(DataToConvertRepository $dataToConvertRepository): Response
    {
        return $this->render('home/index.html.twig');
    }

}
