<?php

namespace GescomBundle\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator;
use GescomBundle\Entity\Product;
use GescomBundle\Entity\ProductSupplier;
use GescomBundle\Form\ProductType;
use GescomBundle\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{
    /**
     * @Route("/product/{page}", name="product", requirements={"page" : "\d+"})
     * @param int $page
     * @return Response
     */
    public function indexAction($page = 1)
    {
        return $this->render('@Gescom/Product/product.html.twig', [
            'data'  => $this->get("gescom.navigator"),
            'documentType' => "Produit",
            'deletionUrl' => $this->generateUrl("delete_product", ['product' => 0]),
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/product/add", name="add_product")
     */
    public function addAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Impossible d'accèder à cette page");
        $product = new Product();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $suppliers = $product->getProductSupplier()["name"];
            $product->resetProductSupplier();
            foreach($suppliers as $supplier){
                $productSupplier = new ProductSupplier();
                $productSupplier->setProduct($product);
                $productSupplier->setSupplier($supplier);
                $em->persist($productSupplier);
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
    /**
     * @param Request $request
     * @param Product $product
     * @Route("/product/edit/{product}", name="edit_product")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("GescomBundle:Product");
        $product = $repository->getProductById($product->getId());
        $suppliers["name"] = $product->getProductSupplier();
        $product->resetProductSupplier();
        $product->setProductSuppliers($suppliers);
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em->flush();
            return $this->redirectToRoute('product');
        }

        return $this->render('@Gescom/Product/addProduct.html.twig', [
            'form'      =>  $form->createView(),
        ]);
    }

    /**
     * @param $product Product
     * @Route("/product/delete/{product}", name="delete_product")
     * @return RedirectResponse
     */
    public function deleteAction(Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $productSuppliers = $em->getRepository("GescomBundle:ProductSupplier")->findBy(['product' => $product]);
        foreach ($productSuppliers as $productSupplier){
            $em->remove($productSupplier);
        }
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('product');
    }    
}
