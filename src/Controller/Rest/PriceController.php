<?php

namespace App\Controller\Rest;

use App\Entity\Price;
use App\Repository\PriceRepository;
use App\Repository\SupermarketRepository;
use App\Service\StringUtils;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use FOS\RestBundle\Controller\Annotations as Rest;

class PriceController extends AbstractController
{
    private $priceRepository;
    private $supermarketRepository;

    public function __construct(PriceRepository $priceRepository, SupermarketRepository $supermarketRepository)
    {
        $this->priceRepository = $priceRepository;
        $this->supermarketRepository = $supermarketRepository;
    }

    /**
     * @Rest\Get("/price/{id}")
     * @return View
     */
    public function getPrice(Price $price = null): View
    {
        $view = "{}";
        try {
            if ($price) {
                $view = View::create($price, Response::HTTP_OK);
            } else {
                $view = View::create(['message'=> 'Not found.'], Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            $view = View::create(['message'=> $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $view;
    }

    /**
     * @Rest\Post("/price")
     * @param Request $request
     * @return View
     */
    public function postSupermarket(Request $request, ValidatorInterface $validator, StringUtils $stringUtils): View
    {
        $view = "{}";
        try {

            $supermaket_id = $request->get('supermaket_id');
            $name = $request->get('name');
            $fprice = $request->get('price');
            if($supermaket_id === null) throw new \Exception('supermaket_id is mandatory.');
            if($name === null) throw new \Exception('name is mandatory.');
            if($fprice === null) throw new \Exception('price is mandatory.');

            $price = new Price();
            $supermarket = $this->supermarketRepository->find($request->get('supermaket_id'));

            if ($supermarket) {

                $price->setKey($stringUtils->removeAccentsAndLowerCase($name));
                $price->setPrice($fprice);
                $price->setSupermarket($supermarket);

                $errors = $validator->validate($price);
                if (count($errors) > 0) {
                    $message = "";
                    foreach ($errors as $error) $message .= $error->getMessage()."\r";
                    throw new \Exception($message);
                }

                $this->priceRepository->save($price);

                $view = View::create($price, Response::HTTP_CREATED);
            } else {
                $view = View::create(['message'=> 'Supermarket not found'], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        } catch (\Exception $e) {
            $view = View::create(['message'=> $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $view;
    }

    /**
     * @Rest\Delete("/price/{id}")
     * @return View
     */
    public function deletePrice(Price $price = null): View
    {
        $view = "{}";
        try {
            if ($price) {
                $this->priceRepository->remove($price);
                $view = View::create(['message'=> 'Deleted!'], Response::HTTP_OK);
            } else {
                $view = View::create(['message'=>'Not found.'], Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            $view = View::create(['message'=> $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $view;
    }
}
