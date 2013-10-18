<?php

namespace Peds\RefProcBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Peds\RefProcBundle\Form\ProductType;
use Peds\EntitiesBundle\Entity\InputProducts;
use Peds\EntitiesBundle\Entity\OutputProducts;
use Peds\EntitiesBundle\Entity\Task;
use Peds\EntitiesBundle\Entity\Product;

class ProductController extends Controller
{
    public function indexAction()
    {
        $form = $this->createForm(new ProductType());
        return $this->render('PedsRefProcBundle:Default:product.html.twig', array('form' => $form->createView(),));
    }

    public function removeAction(Request $request)
    {
        $defaultData = array('');
        $form = $this->createFormBuilder($defaultData)
        ->add('products', 'entity', array(
        'class' => 'PedsEntitiesBundle:Product',))
        ->getForm();

        if ($request->isMethod('POST')) {
            $form->bind($request);
            $em = $this->getDoctrine()->getEntityManager();
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            $product_to_be_removed=$data['products'];
            //print_r($data);
            $product_name=$product_to_be_removed->getShortName();
            $em->remove($product_to_be_removed);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Product '.$product_name.' was deleted successfully!');
        }

        return $this->render('PedsRefProcBundle:Default:product_remove.html.twig', array('form' => $form->createView(),));
    }

    public function removeIdAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $product = $em->getRepository('PedsEntitiesBundle:Product')->find($id);

        if (!$product) {
             throw $this->createNotFoundException(
                'No Product found for id '.$id);
        }
        $em->remove($product);
        $em->flush();
        $this->get('session')->getFlashBag()->add('notice', 'Product with id='.$id.' was deleted successfully!');
        return $this->render('PedsRefProcBundle:Default:product_remove_id.html.twig',array('id' => $id));
    }

    public function newAction()
	{
	$em = $this->getDoctrine()->getEntityManager();
    $product = new Product();
    $form = $this->createForm(new ProductType(), $product);
		

if ($this->getRequest()->isMethod('POST')) {
    $form->bind($this->getRequest());

    if ($form->isValid()) {
        $url = $this->getRequest()->headers->get("referer");
       //print_r($_POST);

        if(isset($_POST['Product']["taskI"])){
            $taskI_id=$_POST['Product']["taskI"];
            //$taskIbool = $em->getRepository('PedsEntitiesBundle:InputProducts')->findOneBy(array('name' => 'foo', 'price' => 19.99));
            $input_product = new InputProducts();
            $taskI = $em->getRepository('PedsEntitiesBundle:Task')->find($taskI_id);
            $input_product->setTask($taskI);
            $input_product->setProduct($product);
            $em->persist($input_product);
            $this->get('session')->getFlashBag()->add('info', 'Product created as input product of task '. $taskI->getShortName());
            //print_r("Product created as input product of task ". $taskI->getShortName());
        }
        if(isset($_POST['Product']["taskO"])){
            $taskO_id=$_POST['Product']["taskO"];
            //$product = $repository->findOneBy(array('name' => 'foo', 'price' => 19.99));
            $output_product = new OutputProducts();
            $taskO = $em->getRepository('PedsEntitiesBundle:Task')->find($taskO_id);
            $output_product->setTask($taskO);
            $output_product->setProduct($product);
            $em->persist($output_product);
            $this->get('session')->getFlashBag()->add('info', 'Product created as output product of task '. $taskO->getShortName());
            //print_r("Product created as output product of task ". $taskO->getShortName());
        }

    	$product = $form->getData();
    	$em->persist($product);
        $em->flush();
        //print_r("Product created!");
    	$this->get('session')->getFlashBag()->add('notice', 'Product created successfully!');
		return $this->redirect($url);

    }//isvalid
}
	return $this->render('PedsRefProcBundle:Default:product.html.twig', array('form' => $form->createView(),));
	}
}
