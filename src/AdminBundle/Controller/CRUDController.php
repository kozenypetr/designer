<?php
/**
 * Created by PhpStorm.
 * User: petr
 * Date: 20.10.18
 * Time: 13:29
 */

namespace AdminBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;


class CRUDController extends Controller {
    /**
     * @param $id
     */
    public function cloneAction($id)
    {
        $object = $this->admin->getSubject();

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id: %s', $id));
        }

        // Be careful, you may need to overload the __clone method of your object
        // to set its id to null !
        $clonedObject = clone $object;

        $clonedObject->setName($object->getName().' (Kopie)');

        $this->admin->create($clonedObject);

        $this->addFlash('sonata_flash_success', 'Produkt zkopírován');

        return new RedirectResponse($this->admin->generateUrl('list'));

        // if you have a filtered list and want to keep your filters after the redirect
        // return new RedirectResponse($this->admin->generateUrl('list', ['filter' => $this->admin->getFilterParameters()]));
    }
}