<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Gedmo\Translatable\TranslatableListener;
use Doctrine\ORM\Query;

use Symfony\Component\Form\Extension\Core\Type\FormType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;



class ShopCatalogController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        /*return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);*/

        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('AppBundle:Category')->findAll();

        $products   = $em->getRepository('AppBundle:Product')->findAll();

        return $this->render('AppBundle:ShopCatalog:index.html.twig', array('products' => $products));
    }


    /**
     * @Route("/kategorie/{slug}", name="shop_catalog_list")
     */
    public function listAction(Request $request, $slug)
    {
        $this->em = $this->getDoctrine()->getManager();

        $category = $this->em->getRepository('AppBundle:Category')->findOneBySlug($slug);

        if (!$category)
        {
            throw $this->createNotFoundException('Kategorie neexistuje');
        }

        return $this->render('AppBundle:ShopCatalog:list.html.twig', array('category' => $category));
    }

    /**
     * @Route("/produkt/{slug}", name="shop_catalog_detail")
     */
    public function detailAction(Request $request, $slug)
    {
        $this->em = $this->getDoctrine()->getManager();

        $product = $this->em->getRepository('AppBundle:Product')->findOneBySlug($slug);

        if (!$product)
        {
            throw $this->createNotFoundException('Produkt neexistuje');
        }

        $attributeForm = $this->createAttributesForm($product);

        return $this->render('AppBundle:ShopCatalog:detail.html.twig', array('product' => $product, 'attributeForm' => ($attributeForm?$attributeForm->createView():null)));
    }


    protected function createAttributesForm($product)
    {
        if ($product->getAttributes())
        {
            $formBuilder = $this->get('form.factory')
                ->createNamedBuilder('attribute', FormType::class, NULL/*hodnoty*/, array('csrf_protection' => false));

            foreach ($product->getAttributes() as $attribute)
            {
                $options = [];
                if ($attribute->getType() == ChoiceType::class)
                {
                    $choices = [];
                    foreach ($attribute->getOptions() as $option)
                    {
                        $choices[$option->getName()] = $option->getName();
                    }

                    $options['choices'] = $choices;
                }

                $options['label'] = $attribute->getName();
                $options['attr']['class'] = $attribute->getClass();
                $formBuilder->add($attribute->getId(), $attribute->getType(), $options);
            }

            $form = $formBuilder->getForm();

            return $form;
        }

        return null;
    }


    public function categoriesAction()
    {
        $this->em = $this->getDoctrine()->getManager();

        $categories = $this->em->getRepository('AppBundle:Category')->findAll();

        return $this->render('AppBundle:ShopCatalog:categories.html.twig', array('categories' => $categories));
    }

}
