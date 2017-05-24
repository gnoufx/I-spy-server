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

		       $positionsGPS = new PositionGPS();
		       $positionsGPS->setLatitude($data->latitude);
		       $positionsGPS->setLongitude($data->longitude);
		       $positionsGPS->setPays($data->pays);
		       $positionsGPS->setVille($data->ville);
		       $positionsGPS->setCodePostal($data->codePostal);
		       $positionsGPS->setAdresse($data->adresse);
		       $positionsGPS->setDatePosition(new \DateTime($data->datePosition));
		       $positionsGPS->setPhone($phone);

		       $em = $this->getDoctrine()->getManager();
		       $em->persist($positionsGPS);

		       try{
		           $em->flush();
		           return $this->json(array('success' => true, 'data' => $positionsGPS));
		       }
		       catch(\Exception $e){
		           return $this->json(array('success' => false, 'message' => $e->getMessage()));
		       }
			}
		}
		return $this->json(array('success' => false, 'message' => "wrongPhone"));
   }

}
