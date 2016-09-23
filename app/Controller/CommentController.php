<?php

namespace SlimDemo\Controller;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use SlimDemo\Entity\Comment;
use SlimDemo\Exception\BadRequestException;
use SlimDemo\Exception\HttpException;
use SlimDemo\Exception\NotFoundException;

final class CommentController
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
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function list(Request $request, Response $response): Response
    {
        $comments = $this->entityManager->getRepository(Comment::class)->findAll();

        $response = $this->addHeaders($response);
        $response->getBody()->write(json_encode($comments));

        return $response;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     *
     * @throws NotFoundException
     */
    public function get(Request $request, Response $response): Response
    {
        $response = $this->addHeaders($response);

        $id = $request->getAttribute('id');

        /** @var Comment $comment */
        $comment = $this->entityManager->getRepository(Comment::class)->find($id);
        if (null === $comment) {
            throw NotFoundException::createForEntity(Comment::class, $id);
        }

        $response->getBody()->write(json_encode($comment));

        return $response;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return int|Response
     *
     * @throws BadRequestException
     */
    public function post(Request $request, Response $response): Response
    {
        $response = $this->addHeaders($response);

        $data = $request->getParsedBody();

        if (!isset($data['comment'])) {
            throw BadRequestException::createForMissingArgument('comment');
        }

        $comment = new Comment();
        $comment->setComment($data['comment']);

        $this->entityManager->persist($comment);
        $this->entityManager->flush($comment);

        $response->getBody()->write(json_encode($comment));

        return $response->withStatus(201);
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return int|Response
     *
     * @throws HttpException
     */
    public function put(Request $request, Response $response): Response
    {
        $response = $this->addHeaders($response);

        $id = $request->getAttribute('id');

        /** @var Comment $comment */
        $comment = $this->entityManager->getRepository(Comment::class)->find($id);
        if (null === $comment) {
            throw NotFoundException::createForEntity(Comment::class, $id);
        }

        $data = $request->getParsedBody();

        if (!isset($data['comment'])) {
            throw BadRequestException::createForMissingArgument('comment');
        }

        $comment->setComment($data['comment']);

        $this->entityManager->persist($comment);
        $this->entityManager->flush($comment);

        $response->getBody()->write(json_encode($comment));

        return $response;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     *
     * @throws NotFoundException
     */
    public function delete(Request $request, Response $response): Response
    {
        $response = $this->addHeaders($response);

        $id = $request->getAttribute('id');

        /** @var Comment $comment */
        $comment = $this->entityManager->getRepository(Comment::class)->find($id);
        if (null === $comment) {
            throw NotFoundException::createForEntity(Comment::class, $id);
        }

        $this->entityManager->remove($comment);
        $this->entityManager->flush();

        $response->getBody()->write(json_encode($comment));

        return $response;
    }

    /**
     * @param Response $response
     *
     * @return Response
     */
    private function addHeaders(Response $response): Response
    {
        return $response->withHeader('Content-Type', 'application/json');
    }
}
