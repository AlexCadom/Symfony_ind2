<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="create_product")
     */

    public function createProduct(): Response
    {
$entityManager = $this->getDoctrine()->getManager();

        $product = new Product();
        $product->setName('Keyboard');
        $product->setPrice(1999);
        //$product->setDescriptions('Ergonomic and stylish!');
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        return new Response('Saved new product with id '.$product->getId());
    }

      /**
     * @Route("/product/{id}", name="product_show")
     */
    public function show($id)
    {    $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id        );    }
        return new Response('Check out this great product: '.$product->getName());
    }
/**
     * @Route("/product/edit/{id}")
     */
    public function update($id)
    {
    $entityManager = $this->getDoctrine()->getManager();
    $product = $entityManager->getRepository(Product::class)->find($id);

    if (!$product) {
        throw $this->createNotFoundException(
            'No product found for id '.$id
        );
    }
    $product->setName('New product name!');
    $entityManager->flush();

    return $this->redirectToRoute('product_show', [
        'id' => $product->getId()
    ]);
}
/**
 * @Route("/product/delete/{id}")
 */
public function delete($id)
{
    $entityManager = $this->getDoctrine()->getManager();
    $product = $entityManager->getRepository(Product::class)->find($id);
    if (!$product) {
        throw $this->createNotFoundException(
            'No product found for deleting id '.$id
        );
    }
  $entityManager->remove($product);
     $entityManager->flush();
   // return    new Response('delete product: '.$product->getName());
 return new Response('Product with id= '.$id.' has been deleted!');
}


}
