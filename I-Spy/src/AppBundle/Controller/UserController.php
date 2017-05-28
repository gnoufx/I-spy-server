<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Phone;
use AppBundle\Entity\PositionGPS;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * User controller.
 */
class UserController extends Controller
{

    /**
     * Creates a new user entity.
     *
     * @Route("users", name="user_new")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
    	$data = json_decode($request->request->get('json'));

    	$user = new User();
    	$user->setMail($data->mail);
    	$user->setPassword($data->password);

    	$em = $this->getDoctrine()->getManager();
    	$em->persist($user);

    	try{
    		$em->flush();
    		return $this->json(array('success' => true, 'data' => $user));
    	}
    	catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e){
    		return $this->json(array('success' => false, 'message' => "uniqueMail"));
    	}
    	catch(\Exception $e){
    		return $this->json(array('success' => false, 'message' => "error"));
    	}
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("user/{id}", name="user_show")
     * @Method("GET")
     */
    public function showAction(Request $request, User $user)
    {
    	$userData = $request->query->get('user');
    	if($user->getMail() == $userData['mail'] && $user->getPassword() == $userData['password']){
    		return $this->json(array('success' => true, 'data' => $user));
    	}
    	return $this->json(array('success' => false, 'message' => "wrongUser"));
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("user", name="user_param_show")
     * @Method("GET")
     */
    public function showParamAction(Request $request)
    {
    	$userData = $request->query->get('user');
    	$em = $this->getDoctrine()->getManager();
		$user = $em->getRepository('AppBundle:User')->findOneBy($userData);
    	if($user){
    		return $this->json(array('success' => true, 'data' => $user));
    	}
    	return $this->json(array('success' => false, 'message' => "wrongUser"));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("user/{id}", name="user_edit")
     * @Method("PUT")
     */
    public function editAction(Request $request, User $user)
    {
    	$userData = $request->query->get('user');
    	if($user->getMail() == $userData['mail'] && $user->getPassword() == $userData['password']){
    		try{
	    		// TODO : selon les champs
    			$this->getDoctrine()->getManager()->flush();
    			return $this->json(array('success' => true, 'data' => $user));
    		}
    		catch(\Exception $e){
    			return $this->json(array('success' => false, 'message' => "error"));
    		}
    	}
    	return $this->json(array('success' => false, 'message' => "wrongUser"));
    }

    /**
     * Deletes a user entity.
     *
     * @Route("user/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $User)
    {
    	$userData = $request->query->get('user');
    	if($user->getMail() == $userData['mail'] && $user->getPassword() == $userData['password']){
	    	try{
	    		$em = $this->getDoctrine()->getManager();
	    		$em->remove($user);
	    		$em->flush();
	    		return $this->json(array('success' => true));
	    	}
	    	catch(\Exception $e){
	    		return $this->json(array('success' => false, 'message' => "error"));
	    	}
	    }
	    return $this->json(array('success' => false, 'message' => "wrongUser"));
    }

    /**
     * Lists all phone entities of a user entity.
     *
     * @Route("user/{id}/phones", name="user_phones_index")
     * @Method("GET")
     */
    public function getPhonesAction(Request $request, User $user)
    {
    	$userData = $request->query->get('user');
    	if($user->getMail() == $userData['mail'] && $user->getPassword() == $userData['password']){
    		return $this->json(array('success' => true, 'data' => $user->getPhones()->getValues()));
    	}
    	return $this->json(array('success' => false, 'message' => "wrongUser"));
    }

    /**
     * Creates a phone entity of a user entity.
     *
     * @Route("user/{id}/phones", name="user_phone_new")
     * @Method("POST")
     */
    public function newPhoneAction(Request $request, User $user)
    {
    	$userData = $request->query->get('user');
    	$phoneData = json_decode($request->request->get('json'));
    	
	if($user->getMail() == $userData['mail'] && $user->getPassword() == $userData['password']){
    		
		$em = $this->getDoctrine()->getManager();
    		$phone = $em->getRepository('AppBundle:Phone')->findOneBy(array(
					'login' => $phoneData->login, 
					'password' => $phoneData->password));
    		
		if($phone) {

    			$user->addPhone($phone);
    			$em->persist($user);
    			try{
    				$em->flush();
					
    				return $this->json(array('success' => true, 'data' => $phone));
    			}
			catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e){
				return $this->json(array('success' => false, 'message' => "uniquePhone"));
			}
    			catch(\Exception $e){
    				return $this->json(array('success' => false, 'message' => "error"));
    			}
    		}
    		else{
    			return $this->json(array('success' => false, 'message' => "wrongPhone"));
    		}
    	}
    	return $this->json(array('success' => false, 'message' => "wrongUser"));
    }

    /**
     * Get all GPS positions from phone {id} of user {id}
     * @Route("user/{id}/phone/{phone_id}/position_gps", name="user_phone_gps_show")
     * @ParamConverter("phone", class="AppBundle:Phone", options={"id" = "phone_id"})
     * @Method("GET")
     */
     public function getPositionGPSFromPhone (Request $request, User $user, Phone $phone)
     {
        $userData = $request->query->get('user');
	$last = (int)$request->query->get('last');

	if($user->getMail() == $userData['mail'] && $user->getPassword() == $userData['password']){

		if ($last) {
			$position_gps = $phone->getPositionsGPS()->get(0);		   			
		} 
		else{
			$position_gps = $phone->getPositionsGPS()->getValues();
		}


		return $this->json(array('success' => true, 'data' => $position_gps));
	}
    	return $this->json(array('success' => false, 'message' => "wrongUser"));

     }
	
    /**
     * Get all contacts from phone {phone_id} of user {id}
     * @Route("user/{id}/phone/{phone_id}/contacts", name="user_phone_contacts_show")
     * @ParamConverter("phone", class="AppBundle:Phone", options={"id" = "phone_id"})
     * @Method("GET")
     */
     public function getContactsFromPhone (Request $request, User $user, Phone $phone)
     {
        $userData = $request->query->get('user');

	if($user->getMail() == $userData['mail'] && $user->getPassword() == $userData['password']){
    		
		$contacts = $phone->getContacts()->getValues();

		return $this->json(array('success' => true, 'data' => $contacts));
	}
    	return $this->json(array('success' => false, 'message' => "wrongUser"));

     }	
}
