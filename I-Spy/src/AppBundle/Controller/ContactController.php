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
     * @Route("contacts", name="contact_new")
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

			   $contacts = array();
			   foreach ($data as $c) {
			       $contact = new Contact();

			       $contact->setNom($c->nom);
			       $contact->setNumero($c->numero);
			       $contact->setIdRef($c->idRef);
			       $contact->setPhone($phone);
			       
			       $contacts[] = $contact;
			       $em->persist($contact);
			   }

		       try{
		           $em->flush();
		           return $this->json(array('success' => true, 'data' => null));
		       }
		       catch(\Exception $e){
		           return $this->json(array('success' => false, 'message' => $e->getMessage()));
		       }
			}
		}
		return $this->json(array('success' => false, 'message' => "wrongPhone"));
   }

}
