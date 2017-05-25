<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Contact controller.
 */
class ContactController extends Controller
{

    /**
     * Creates a new contact entity.
     *
     * @Route("contact", name="contact_new")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
       $phoneData = $request->query->get('phone');
       $em = $this->getDoctrine()->getManager();
       $phone = $em->getRepository('AppBundle:Phone')->findOneBy($phoneData);

       if($phone->getLogin() == $phoneData['login'] && $phone->getPassword() == $phoneData['password']){
	       if($phone){
				$data = json_decode($request->request->get('json'));

		       $contact = new Contact();
		       $contact->setNom($data->nom);
		       $contact->setNumero($data->numero);
		       $contact->setIdRef($data->idRef);
		       $contact->setPhone($phone);

		       $em = $this->getDoctrine()->getManager();
		       $em->persist($contact);

		       try{
		           $em->flush();
		           return $this->json(array('success' => true, 'data' => $contact));
		       }
		       catch(\Exception $e){
		           return $this->json(array('success' => false, 'message' => $e->getMessage()));
		       }
			}
		}
		return $this->json(array('success' => false, 'message' => "wrongPhone"));
   }

}
