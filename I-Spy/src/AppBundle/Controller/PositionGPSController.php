<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PositionGPS;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * PositionGPS controller.
 */
class PositionGPSController extends Controller
{

    /**
     * Creates a new positionGPS entity.
     *
     * @Route("positionsGPS", name="positionsGPS_new")
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

			   $em = $this->getDoctrine()->getManager();

			   foreach ($data as $pos) {
			   	   $positionsGPS = new PositionGPS();

			       $positionsGPS->setLatitude($pos->latitude);
			       $positionsGPS->setLongitude($pos->longitude);
			       $positionsGPS->setPays($pos->pays);
			       $positionsGPS->setVille($pos->ville);
			       $positionsGPS->setCodePostal($pos->codePostal);
			       $positionsGPS->setAdresse($pos->adresse);
			       $positionsGPS->setDatePosition(new \DateTime($pos->datePosition));
			       $positionsGPS->setPhone($phone);

			       $em->persist($positionsGPS);
			   }

		       try{
		           $em->flush();
		           return $this->json(array('success' => true, 'data' => $positionsGPS));
		       }
		       catch(\Exception $e){
		       		var_dump($data);
		           return $this->json(array('success' => false, 'message' => $e->getMessage()));
		       }
			}
		}
		return $this->json(array('success' => false, 'message' => "wrongPhone"));
   }

}
