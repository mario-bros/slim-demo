<?php

namespace SlimDemo\Controller;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\NotFoundException;
use SlimDemo\Entity\Comment;
use SlimDemo\Repository\CommentRepository;

class CommentController
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws NotFoundException
     */
    public function get(Request $request, Response $response)
    {
        $id = $request->getAttribute('id');

        /** @var Comment $comment */
        $comment = $this->entityManager->getRepository(CommentRepository::class)->find($id);
        if (null === $comment) {
            $response->getBody()->write(json_encode(['error' => sprintf('Unknown comment with id %s', $id)]));

            throw new NotFoundException($request, $response);
        }

        $response->getBody()->write($comment->toJson());

        return $response;
    }
}
