<?php

namespace AppBundle\Service;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class Validate
{

    private $validator;

    private $em;

    /**
     * Validate constructor.
     * @param ValidatorInterface $validator
     * @param RegistryInterface $registry
     */
    public function __construct(ValidatorInterface $validator,RegistryInterface $registry)
    {
        $this->validator=$validator;
        $this->em=$registry;
    }


    public function validateRequest($data)
    {
        $errors = $this->validator->validate($data);

        $errorsResponse = array();

        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $errorsResponse[] = [
                'field' => $error->getPropertyPath(),
                'message' => $error->getMessage()
            ];
        }


        if (count($errors))
        {

            $reponse=array(

                'message'=>'validation errors',
                 'errors'=>$errorsResponse,
                   'result'=>null

            );

            return $reponse;
        }else{

            $reponse=[];
            return $reponse;




        }

    }



}