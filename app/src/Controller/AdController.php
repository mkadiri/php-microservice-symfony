<?php

namespace App\Controller;

use App\Service\Adaptor\AdEntityAdaptor;
use App\Exception\AdNotFoundException;
use App\Exception\PermissionDeniedException;
use App\Exception\TechnicalErrorException;
use App\Exception\UserNotFoundException;
use App\Service\AdService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ad/list", methods={"GET"})
     */
    public function listAll(AdService $adService, AdEntityAdaptor $adEntityAdaptor)
    {
        $adEntities = $adService->getAll();
        $data = [];

        foreach ($adEntities as $adEntity) {
            $data[] = $adEntityAdaptor->toArray($adEntity);
        }

        return $this->json($data);
    }

    /**
     * @Route("/ad", methods={"POST"})
     */
    public function new(AdService $adService, AdEntityAdaptor $adEntityAdaptor, Request $request)
    {
        $bodyData = json_decode($request->getContent(), true);

        $authToken = $bodyData['authToken'];
        $title = $bodyData['title'];
        $description = $bodyData['description'];
        $price = $bodyData['price'];

        try {
            $adEntity = $adService->create($authToken, $title, $description, $price);
            $data = $adEntityAdaptor->toArray($adEntity);

            return $this->json($data);

        } catch (UserNotFoundException $e) {
            return $this->json(['error' => 'No user found matching the user auth token'], Response::HTTP_INTERNAL_SERVER_ERROR);

        } catch (TechnicalErrorException $e) {
            return $this->json(['error' => 'There was a problem creating the ad'], Response::HTTP_INTERNAL_SERVER_ERROR);

        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/ad/{id}", methods={"PUT"})
     */
    public function edit($id, AdService $adService, AdEntityAdaptor $adEntityAdaptor, Request $request)
    {
        $bodyData = json_decode($request->getContent(), true);

        $authToken = $bodyData['authToken'];
        $title = $bodyData['title'];
        $description = $bodyData['description'];
        $price = $bodyData['price'];

        try {
            $adEntity = $adService->update($authToken, $id, $title, $description, $price);
            $data = $adEntityAdaptor->toArray($adEntity);

            return $this->json($data);

        } catch (UserNotFoundException $e) {
            return $this->json(['error' => 'No user found matching the user auth token'], Response::HTTP_INTERNAL_SERVER_ERROR);

        } catch (AdNotFoundException $e) {
            return $this->json(['error' => 'Could not find ad with id ' . $id], Response::HTTP_INTERNAL_SERVER_ERROR);

        } catch (PermissionDeniedException $e) {
            return $this->json(['error' => 'You are not permitted to edit this ad'], Response::HTTP_INTERNAL_SERVER_ERROR);

        } catch (TechnicalErrorException $e) {
            return $this->json(['error' => 'There was a problem updating the ad'], Response::HTTP_INTERNAL_SERVER_ERROR);

        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
