<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Form\ContactType;
use AppBundle\Entity\Contact;


class ContactController extends Controller
{

    /**
     * @Route("/odeslat-dotaz", name="contact_form")
     */
    public function contactAction(Request $request)
    {
        // odeslani dotazu na email
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact, array(
            // To set the action use $this->generateUrl('route_identifier')
            'action' => $this->generateUrl('contact_form'),
            'method' => 'POST'
        ));

        $form->handleRequest($request);
        $json = array('status' => 'ERROR');

        if (empty($contact->getSubject()) && $form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            // odeslani mailu
            $data = ['contact' => $contact, 'referer' => $referer = $request->headers->get('referer')];

            $subject = 'Dotaz na GOWOOD. Děkujeme.';

            $template = 'AppBundle:Email/contact:query.html.twig';

            $this->get('shop.mailer')->send($subject, 'Váš dotaz na nás', $contact->getEmail(), $data, $template);

            $json['status'] = 'OK';
        }

        return new JsonResponse($json);
    }


    public function formAction()
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact, array(
            // To set the action use $this->generateUrl('route_identifier')
            'action' => $this->generateUrl('contact_form'),
            'method' => 'POST'
        ));

        return $this->render('AppBundle:Contact:form.html.twig', array('form' => $form->createView()));
    }

}
