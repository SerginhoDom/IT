<?php

namespace App\Service;
use App\Entity\Author;
use App\Entity\Conference;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Book;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use function Sodium\add;

class ConferenceService {
    private $commentRepository;
    private $conferenceRepository;
    private $serializer;

    public function __construct(ConferenceRepository $conferenceRepository, CommentRepository $commentRepository) {
        $this->conferenceRepository = $conferenceRepository;
        $this->commentRepository = $commentRepository;

        $metaDataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalaizer = new ObjectNormalizer($metaDataFactory);
        $this->serializer = new Serializer([$normalaizer], array(new JsonEncoder()));
    }

    public function getAllConferences(): string
    {
        $responce = $this->conferenceRepository->findAll();
        $responceJson = $this->serializer->serialize($responce, 'json', ['groups' => 'conference']);
        return $responceJson;
    }

    public function getConferenceById(Request $request){
        $buff = $this->conferenceRepository->findBy(['id' => $request->get('id')]);
        $allComents = $this->commentRepository->getCommentsByConference($buff[0]);

        $buffJson = $this->serializer->serialize($buff, 'json', ['groups' => 'conference']);
        $allComentsJson = $this->serializer->serialize($allComents, 'json', ['groups' => 'comment']);

        $responceJson = $this->serializer->serialize([$buffJson, $allComentsJson], 'json');
        return $responceJson;
    }
}