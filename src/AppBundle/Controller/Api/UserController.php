<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\User;
use AppBundle\Service\Validate;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends Controller
{

    /**
     * @Route("/api/user/{id}", name="show_user", methods={"GET"})
     * @Method("GET")
     */
    public function showUser($id)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);


        if (empty($user)) {
            $response = array(
                'message' => 'user not found',
                'error' => null,
                'result' => null
            );

            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $data = $this->get('jms_serializer')->serialize($user, 'json');


        $response = array(

            'message' => 'success',
            'errors' => null,
            'result' => json_decode($data)

        );

        return new JsonResponse($response, 200);


    }
    /**
     * @Route("/api/users",name="list_users", methods={"GET"})
     * @Method("GET")
     */

    public function listUsers()
    {

        $users=$this->getDoctrine()->getRepository('AppBundle:User')->findAll();

        if (!count($users)){
            $response=array(

                'message'=>'No users found!',
                'errors'=>null,
                'result'=>null

            );


            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }


        $data=$this->get('jms_serializer')->serialize($users,'json');

        $response=array(

            'message'=>'success',
            'errors'=>null,
            'result'=>json_decode($data)

        );

        return new JsonResponse($response,200);


    }


    /**
     * @param ObjectManager $om
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Request $request
     * @return Response
     * @Route("/api/register" ,name="create_user", methods={"POST"})
     * @Method({"POST"})
     */


    public function register(UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {
        $user = new User();

        $data = json_decode($request->getContent(),true);

        $em = $this->getDoctrine()->getManager();

        if(strlen($data['password'] < 6)){
            $response=array(

                'message'=>'Password should be at least 6 characters !',
                'errors'=>null,
                'result'=>null

            );
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);

        }
        elseif ($data['password'] == $data['passwordConfirmation']) {

      $user->setUsername($data['username']);
      $user->setRoles($data['roles']);
      $user->setEmail($data['email']);
      $user->setPassword($data['password']);
      $user->setPasswordConfirmation($data['passwordConfirmation']);


      $em->persist($user);
      $em->flush();
      return new Response('created  success');
       } else{

      $response=array(

          'message'=>'confirm pass!',
          'errors'=>null,
          'result'=>null

      );

      return new JsonResponse($response, Response::HTTP_NOT_FOUND);
      }


    }

    /**
     * @param Request $request
     * @param $id
     * @param Validate $validate
     * @return JsonResponse
     * @Route("/api/user/{id}/edit",name="update_user", methods={"PUT"})
     * @Method({"PUT"})
     */
    public function updateUser(Request $request,$id,Validate $validate)
    {

        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id);

        if (empty($user))
        {
            $response=array(

                'message'=>'user Not found !',
                'errors'=>null,
                'result'=>null

            );

            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $body=$request->getContent();


        $data=$this->get('jms_serializer')->deserialize($body,'AppBundle\Entity\User','json');


        $reponse= $validate->validateRequest($data);

        if (!empty($reponse))
        {
            return new JsonResponse($reponse, Response::HTTP_BAD_REQUEST);

        }

        $user->setUsername($data->getUsername());
        $user->setPassword($data->getPassword());

        $em=$this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $response=array(

            'message'=>'user updated!',
            'errors'=>null,
            'result'=>null

        );

        return new JsonResponse($response,200);

    }

    /**
     * @Route("/api/user/{id}",name="delete_user", methods={"DELETE"})
     * @Method({"DELETE"})
     */

    public function deleteProjet($id)
    {
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id);

        if (empty($user)) {

            $response=array(

                'message'=>'user Not found !',
                'errors'=>null,
                'result'=>null

            );


            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $em=$this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        $response=array(

            'message'=>'user deleted !',
            'errors'=>null,
            'result'=>null

        );


        return new JsonResponse($response,200);



    }

}
