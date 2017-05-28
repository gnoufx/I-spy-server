<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Phone;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Phone controller.
 */
class PhoneController extends Controller
{

    /**
     * Creates a new phone entity.
     *
     * @Route("phones", name="phones_new")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
       $data = json_decode($request->request->get('json'));

       $phone = new Phone();
       $phone->setLogin($data->login);
       $phone->setPassword($data->password);

       $em = $this->getDoctrine()->getManager();
       $em->persist($phone);

       try{
           $em->flush();
       }
       catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e){
           return $this->json(array('success' => false, 'message' => "uniqueLogin"));
       }
       catch(\Exception $e){
           return $this->json(array('success' => false, 'message' => "error"));
       }

       return $this->json(array('success' => true, 'data' => $phone));
   }

    /**
     * Finds and displays a phone entity.
     *
     * @Route("phone/{id}", name="phone_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Phone $phone)
    {
        $phoneData = $request->query->get('phone');
        if($phone->getLogin() == $phoneData['login'] && $phone->getPassword() == $phoneData['password']){
            return $this->json(array('success' => true, 'data' => $phone));
        }
        return $this->json(array('success' => false, 'message' => "wrongPhone"));
    }

    /**
     * Finds and displays a phone entity.
     *
     * @Route("phone", name="phone_param_show")
     * @Method("GET")
     */
    public function showParamAction(Request $request)
    {
        $phoneData = $request->query->get('phone');
        $em = $this->getDoctrine()->getManager();
        $phone = $em->getRepository('AppBundle:Phone')->findOneBy($phoneData);
        if($phone){
            return $this->json(array('success' => true, 'data' => $phone));
        }
        return $this->json(array('success' => false, 'message' => "wrongPhone"));
    }

    /**
     * Displays a form to edit an existing phone entity.
     *
     * @Route("phone/{id}", name="phone_edit")
     * @Method("PUT")
     */
    public function editAction(Request $request, Phone $phone)
    {
        $phoneData = $request->query->get('phone');
        if($phone->getLogin() == $phoneData['login'] && $phone->getPassword() == $phoneData['password']){
        	try{
                // TODO : selon les champs
               $this->getDoctrine()->getManager()->flush();
               return $this->json(array('success' => true, 'data' => $phone));
           }
           catch(\Exception $e){
               return $this->json(array('success' => false, 'message' => "error"));
           }
       }
       return $this->json(array('success' => false, 'message' => "wrongPhone"));
   }

    /**
     * Deletes a phone entity.
     *
     * @Route("phone/{id}", name="phone_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Phone $phone)
    {
        $phoneData = $request->query->get('phone');
        if($phone->getLogin() == $phoneData['login'] && $phone->getPassword() == $phoneData['password']){
           try{
               $em = $this->getDoctrine()->getManager();
               $em->remove($phone);
               $em->flush();
               return $this->json(array('success' => true));
           }
           catch(\Exception $e){
               return $this->json(array('success' => false, 'message' => "error"));
           }
       }
       return $this->json(array('success' => false, 'message' => "wrongPhone"));
   }

   /**
     * Displays a form to edit an existing contact entity.
     *
     * @Route("phone/{id}/contacts", name="phone_contact_edit")
     * @Method("PUT")
     */
    public function editContactsAction(Request $request, Phone $phone)
    {
        $phoneData = $request->query->get('phone');
        $em = $this->getDoctrine()->getManager();
        $phone = $em->getRepository('AppBundle:Phone')->findOneBy($phoneData);

        if($phone->getLogin() == $phoneData['login'] && $phone->getPassword() == $phoneData['password']){
          if($phone){
            $data = json_decode($request->request->get('json'));

            $repoC = $em->getRepository('AppBundle:Contact');
            foreach ($data as $c) {
               $contact = $repoC->findOneBy(array('idRef' => $c->idRef));

               if($contact){
                 $contact->setNom($c->nom);
                 $contact->setNumero($c->numero);
                 
                 $em->persist($contact);
               }
            }

            try{
                  // TODO : selon les champs
                 $em->flush();
                 return $this->json(array('success' => true, 'data' => null));
             }
             catch(\Exception $e){
                 return $this->json(array('success' => false, 'message' => "error"));
             }
           }
       }
       return $this->json(array('success' => false, 'message' => "wrongPhone"));
   }

}
