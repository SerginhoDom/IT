<?php

namespace App\Controller;

use App\Service\ConferenceService;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ConferenceRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Twig\Environment;
use function MongoDB\BSON\toJSON;

class ConferenceController extends AbstractController
{
    private ConferenceService $conferenceService;

    public function __construct(ConferenceService $conferenceService){
        $this->conferenceService = $conferenceService;
    }

    #[Route('/', name: 'homepage')]
    public function getAllConferences(): Response
    {
        $responceJson = $this->conferenceService->getAllConferences();
        return new Response(
            $responceJson,
            Response::HTTP_OK,
            ['content-type' => 'json']);
    }

    #[Route('/conference/{id}', name: 'conference')]
    public function getConferenceById(Request $request): Response
    {
        $responceJson = $this->conferenceService->getConferenceById($request);
        return new Response(
            $responceJson,
            Response::HTTP_OK,
            ['content-type' => 'json']);
    }
}
