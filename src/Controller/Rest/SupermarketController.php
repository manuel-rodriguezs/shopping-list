<?php

namespace App\Controller\Rest;

use App\Entity\Supermarket;
use App\Repository\SupermarketRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class SupermarketController extends AbstractFOSRestController
{

    private $supermarketRepository;

    public function __construct(SupermarketRepository $supermarketRepository)
    {
        $this->supermarketRepository = $supermarketRepository;
    }

    /**
     * @Rest\Get("/supermarkets")
     * @return View
     */
    public function getSupermarkets(): View
    {
        $view = "{}";
        try {
            $supermarkets = $this->supermarketRepository->findAll();
            $view = View::create($supermarkets, Response::HTTP_OK);
        } catch (\Exception $e) {
            $view = View::create(['message'=> $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $view;
    }

    /**
     * @Rest\Post("/supermarket")
     * @param Request $request
     * @return View
     */
    public function postSupermarket(Request $request, ValidatorInterface $validator): View
    {
        $view = "{}";
        try {
            $supermarket = new Supermarket();

            $supermarket->setName($request->get('name'));

            $errors = $validator->validate($supermarket);
            if (count($errors) > 0) {
                $message = "";
                foreach ($errors as $error) $message .= $error->getMessage() . "\r";
                throw new \Exception($message);
            }

            $this->supermarketRepository->save($supermarket);

            $view = View::create($supermarket, Response::HTTP_CREATED);
        } catch (UniqueConstraintViolationException $e) {
            $view = View::create(['message'=> 'Duplicated Entry!'], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            $view = View::create(['message'=> $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $view;
    }

    /**
     * @Rest\Delete("/supermarket/{id}")
     * @return View
     */
    public function deleteSupermarket(Supermarket $supermarket = null): View
    {
        $view = "{}";
        try {
            if ($supermarket)
            {
                $this->supermarketRepository->remove($supermarket);
                $view = View::create(['message'=> 'Deleted!'], Response::HTTP_OK);

            } else {
                $view = View::create(['message'=> 'Not found.'], Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            $view = View::create(['message'=> $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $view;
    }
}