<?php

namespace GescomBundle\Controller;

use GescomBundle\Entity\Product;
use GescomBundle\Entity\ProductSupplier;
use GescomBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{
    /**
     * @Route("/product", name="product")
     */
    public function indexAction()
    {
        // we retrieve all data from table through the entity
        $products = $this->getDoctrine()->getRepository('GescomBundle:Product')->findAll();
        return $this->render('@Gescom/Product/product.html.twig', [
            'products'  => $products,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/product/add", name="add_product")
     */
    public function addAction(Request $request)
    {
        $product = new Product();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form  ->isValid()){
            $suppliers = $product->getProductSupplier()["name"];
            // suppliers are stored with a top level "name" unecessary
            // we must remove this "name" level with this custom method
            $product->resetProductSupplier();
            foreach($suppliers as $supplier){
                // create a new link entity
                $productSupplier = new ProductSupplier();
                // set product
                $productSupplier->setProduct($product);
                // set supplier
                $productSupplier->setSupplier($supplier);
                $em->persist($productSupplier);
                // add supplier to product
                $product->addProductSupplier($productSupplier);
            }
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('product');
        }

        return $this->render('@Gescom/Product/addProduct.html.twig', [
            'form'      =>  $form->createView(),
        ]);
    }
}
