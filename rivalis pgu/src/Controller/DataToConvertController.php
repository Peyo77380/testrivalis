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

#[Route('/conversion')]
class DataToConvertController extends AbstractController
{
    #[Route('/', name: 'app_data_to_convert_index', methods: ['GET'])]
    public function index(DataToConvertRepository $dataToConvertRepository): Response
    {
        return $this->render('data_to_convert/index.html.twig', [
            'data_to_converts' => $dataToConvertRepository->findByUser($this->getUser()),
        ]);
    }

    #[Route('/new', name: 'app_data_to_convert_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DataToConvertRepository $dataToConvertRepository): Response
    {
        $dataToConvert = new DataToConvert();
        $form = $this->createForm(DataToConvertType::class, $dataToConvert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dataToConvert->setUser($this->getUser());

            $dataToConvertRepository->save($dataToConvert, true);

            return $this->redirectToRoute('app_data_to_convert_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('data_to_convert/new.html.twig', [
            'data_to_convert' => $dataToConvert,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_data_to_convert_show', methods: ['GET'])]
    public function show(DataToConvert $dataToConvert): Response
    {
        if ($this->getUser() !== $dataToConvert->getUser()) {
            return $this->redirectToRoute('connect_google_start');
        }
        return $this->render('data_to_convert/show.html.twig', [
            'data_to_convert' => $dataToConvert,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_data_to_convert_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DataToConvert $dataToConvert, DataToConvertRepository $dataToConvertRepository): Response
    {
        if ($this->getUser() !== $dataToConvert->getUser()) {
            return $this->redirectToRoute('connect_google_start');
        }

        $form = $this->createForm(DataToConvertType::class, $dataToConvert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dataToConvertRepository->save($dataToConvert, true);

            return $this->redirectToRoute('app_data_to_convert_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('data_to_convert/edit.html.twig', [
            'data_to_convert' => $dataToConvert,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_data_to_convert_delete', methods: ['POST'])]
    public function delete(Request $request, DataToConvert $dataToConvert, DataToConvertRepository $dataToConvertRepository): Response
    {
        if ($this->getUser() !== $dataToConvert->getUser()) {
            return $this->redirectToRoute('connect_google_start');
        }

        if ($this->isCsrfTokenValid('delete' . $dataToConvert->getId(), $request->request->get('_token'))) {
            $dataToConvertRepository->remove($dataToConvert, true);
        }

        return $this->redirectToRoute('happ_data_to_convert_indexome', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/export', name: 'app_data_to_convert_export', methods: ['GET'])]
    public function export(Request $request, DataToConvert $dataToConvert, DataToConvertRepository $dataToConvertRepository)
    {
        if ($this->getUser() !== $dataToConvert->getUser()) {
            return $this->redirectToRoute('connect_google_start');
        }

        $pdf = new PdfManager();

        $html = $this->render('data_to_convert/export.html.twig', ['user' => $this->getUser(), 'message' => $dataToConvert]);

        $response = new Response();
        $filename = 'message.pdf';

        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', 'pdf' );
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '";');

        // Send headers before outputting anything
        $response->sendHeaders();

        $response->setContent( $pdf->generate($html));

        return $response;
    }
}
