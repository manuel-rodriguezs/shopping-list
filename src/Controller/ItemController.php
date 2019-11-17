<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use App\Services\ItemService;
use App\Entity\Item;

class ItemController extends AbstractController
{
    /**
     * @Route("/items", name="items", methods={"GET"})
     */
    public function index()
    {
        $itemsRepo = $this->getDoctrine()->getRepository(Item::class);

        $items = $itemsRepo->findAll();

        return $this->render('item/index.html.twig', [
            'items' => $items,
        ]);
    }

    /**
     * @Route("/item", name="item.post", methods={"POST"})
     */
    public function save(Request $request, ItemService $itemService)
    {
        try {
            $description = $request->get('description');
            $itemService->validateDescription($description);

            $em = $this->getDoctrine()->getManager();

            $item = new Item();
            $item->setDescription($description);
            $item->setKey($itemService->generateKey($description));

            $em->persist($item);

            $em->flush();

        } catch (UniqueConstraintViolationException $e) {
            $this->addFlash(
                'danger',
                'There is already an item with this name'
            );
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                'Error: ' . $e->getMessage()
            );
        }

        return $this->redirectToRoute('items');
    }

    /**
     * @Route("/item/{id}", name="item.delete")
     */
    public function delete(Item $item)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $em->remove($item);

            $em->flush();

        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                'Error: ' . $e->getMessage()
            );
        }

        return $this->redirectToRoute('items');
    }
}
