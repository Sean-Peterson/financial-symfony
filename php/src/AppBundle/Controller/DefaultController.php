<?php

namespace AppBundle\Controller;

use AppBundle\Entity\City;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\UserInfoType;
use Doctrine\ORM\EntityManagerInterface;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function resultsAction(Request $request, EntityManagerInterface $em)
    {
      $repo = $this->getDoctrine()->getRepository('AppBundle:City');
      $entries = $repo->findAll();
      $form = $this->createForm(UserInfoType::class);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        $income = $data['income'];
        $city = $data['city'];
        $marital_status = $data['marital_status'];
        // echo '<pre>';
        // var_dump($marital_status);
        // var_dump($income);
        // var_dump($city);
        // '</pre>';
        $state='';
        $col = 0;
        $rent = 0;
        foreach ($entries as $value) {
          // var_dump($value);
          if ($value->getCity() == $city) {
            $state = $value->getState();
            $col = $value->getCol();
            $rent = $value->getRent();
          }
        }
        $repository = $em->getRepository('AppBundle:City');
        $city_object = $repository->findOneByCity($city);
        // var_dump('The state is'.$state.'   did it work?');
        $authorization = 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBUElfS0VZX01BTkFHRVIiLCJodHRwOi8vdGF4ZWUuaW8vdXNlcl9pZCI6IjU5MTlmMTlmZTkyMWMwMzY2NjZmMTMxZiIsImh0dHA6Ly90YXhlZS5pby9zY29wZXMiOlsiYXBpIl0sImlhdCI6MTQ5NDg3MjQ3OX0.grP0a9fG_4NdaOaRWk5H-lwG9XOfcgic8eUmhTPF7Tc';
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_RETURNTRANSFER => 1,
          CURLOPT_URL => 'https://taxee.io/api/v2/state/2017/'.$state,
          CURLOPT_USERAGENT => 'SeanCodes'
        ));

        curl_setopt($curl, CURLOPT_HTTPHEADER,array('Content-Type: application/json', $authorization));
        $articles = curl_exec($curl);
        curl_close($curl);
        $stuff = json_decode($articles, true);
        // var_dump($stuff);
        if (isset($stuff[$marital_status]['income_tax_brackets'])) {
          for($i=0;$i<count($stuff[$marital_status]['income_tax_brackets']);$i++){
            if(60000<$stuff[$marital_status]['income_tax_brackets'][$i]['bracket']){
              $tax_array_number=$i-1;
              break;
            }else{
              $tax_array_number=count($stuff[$marital_status]['income_tax_brackets'])-1;
            }
          }

          $state_tax=(float)$stuff[$marital_status]['income_tax_brackets'][$tax_array_number]['marginal_rate'];

        }

        $authorization = 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBUElfS0VZX01BTkFHRVIiLCJodHRwOi8vdGF4ZWUuaW8vdXNlcl9pZCI6IjU5MTlmMTlmZTkyMWMwMzY2NjZmMTMxZiIsImh0dHA6Ly90YXhlZS5pby9zY29wZXMiOlsiYXBpIl0sImlhdCI6MTQ5NDg3MjQ3OX0.grP0a9fG_4NdaOaRWk5H-lwG9XOfcgic8eUmhTPF7Tc';
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://taxee.io/api/v2/federal/2017',
        CURLOPT_USERAGENT => 'SeanCodes'
        ));
        curl_setopt($curl, CURLOPT_HTTPHEADER,array('Content-Type: application/json', $authorization));
        $articles = curl_exec($curl);
        curl_close($curl);
        $stuff = json_decode($articles, true);

        for($i=0;$i<count($stuff[$marital_status]['income_tax_brackets']);$i++){
          if($income<$stuff[$marital_status]['income_tax_brackets'][$i]['bracket']){
            $tax_array_number=$i-1;
            break;
          }else{
            $tax_array_number=count($stuff[$marital_status]['income_tax_brackets'])-1;
          }
        }

        $fed_tax=(float)$stuff[$marital_status]['income_tax_brackets'][$tax_array_number]['marginal_rate'];



        echo '<pre>';
          var_dump($fed_tax);
          echo '</pre>';
          $result='ham';
          return $this->render('results.html.twig',
          [
          'result' => $result
          ]);
        }
        return $this->render('default/index.html.twig',
        [
        'form' => $form ->createView(),
        ]);
      }
    }
