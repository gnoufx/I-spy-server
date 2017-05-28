<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Message controller.
 */
class MessageController extends Controller
{

    /**
     * Creates a new message entity.
     *
     * @Route("messages", name="message_new")
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
			       $message = new Message();

			       $message->setNumero($c->numero);
			       $message->setType($c->type);
			       $date = new \DateTime();
			       $date->setTimestamp($c->dateMessage);
			       $message->setDateMessage($date);
			       $message->setContenu($c->contenu);
			       $message->setPhone($phone);

			       $contact = $em->getRepository('AppBundle:Contact')->findOneBy(array('phone' => $phone, 'numero' => $c->numero));
			       if($contact)
			       	$message->setContact($contact);
			       
			       $contacts[] = $message;
			       $em->persist($message);
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
