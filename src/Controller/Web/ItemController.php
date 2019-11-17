<?php

namespace App\Controller\Web;

use App\Repository\ItemRepository;
use App\Repository\SupermarketRepository;
use App\Service\ItemsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\StringUtils;
use App\Entity\Item;

class ItemController extends AbstractController
{
    private $itemRepository;
    private $supermarketRepository;
    private $session;

    public function __construct(
        ItemRepository $itemRepository,
        SupermarketRepository $supermarketRepository,
        SessionInterface $session)
    {
        $this->itemRepository = $itemRepository;
        $this->supermarketRepository = $supermarketRepository;
        $this->session = $session;
    }

    /**
     * @Route("/items/{supermarketId?}", name="items", methods={"GET"})
     */
    public function index($supermarketId = null, ItemsService $itemsService)
    {
        $this->session->set('supermarketId', $supermarketId);

        $items = $this->itemRepository->findAll();

        if ($supermarketId) {
            $supermarket = $this->supermarketRepository->find($supermarketId);

            if ($supermarket) {
                $items = $itemsService->mergePricesWithItems($items, $supermarket);
            }
        }

        return $this->render('item/index.html.twig', [
            'items' => $items,
            'supermarkets' => $this->supermarketRepository->findAll(),
            'supermarketId'=> $supermarketId
        ]);
    }

    /**
     * @Route("/item", name="item.post", methods={"POST"})
     */
    public function save(Request $request, ValidatorInterface $validator, StringUtils $stringUtils)
    {
        try {
            $description = $request->get('description');

            $item = new Item();
            $item->setDescription($description);
            $item->setKey($stringUtils->removeAccentsAndLowerCase($description));

            $errors = $validator->validate($item);
            if (count($errors) > 0) {
                $message = "";
                foreach ($errors as $error) $message .= $error->getMessage()."\r";
                throw new \Exception($message);
            }

            $this->itemRepository->save($item);

        } catch (UniqueConstraintViolationException $e) {
            $this->addFlash(
                'danger',
                'There is already an item with this name.'
            );
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                'Error: ' . $e->getMessage()
            );
        }

        return $this->redirectToRoute('items', ['supermarketId' => $this->session->get('supermarketId')]);
    }

    /**
     * @Route("/item/{id}", name="item.delete")
     */
    public function delete(Item $item = null)
    {
        try {
            if ($item)
            {
                $this->itemRepository->remove($item);

            } else {
                $this->addFlash(
                    'warning',
                    'Item not found.'
                );
            }
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                'Error: ' . $e->getMessage()
            );
        }

        return $this->redirectToRoute('items', ['supermarketId' => $this->session->get('supermarketId')]);
    }
}
