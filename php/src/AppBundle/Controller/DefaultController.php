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
        $ny = $repository->findOneByCity('New-York');
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
        if (isset($stuff[$marital_status]['income_tax_brackets'])) {
          for($i=0;$i<count($stuff[$marital_status]['income_tax_brackets']);$i++){
            if(60000<$stuff[$marital_status]['income_tax_brackets'][$i]['bracket']){
              $tax_array_number=$i-1;
              break;
            }else{
              $tax_array_number=count($stuff[$marital_status]['income_tax_brackets'])-1;
            }
          }
          $state_tax=.01*((float)$stuff[$marital_status]['income_tax_brackets'][$tax_array_number]['marginal_rate']);
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

        $fed_tax=.01*((float)$stuff[$marital_status]['income_tax_brackets'][$tax_array_number]['marginal_rate']);

        if ($city != "New-York") {
          $user_col = ($city_object->getCol()*.0001) * $ny->getCol();
          $rent = $city_object->getRent();
          $user_state = strtoupper($city_object->getState());
        }else{
          $user_col = $ny->getCol();
          $rent = $ny->getRent();
          $user_state = strtoupper($ny->getState());
        }

        $monthly_take_home = intval($income/12 - ((($income/12)*$state_tax)+(($income/12)*$fed_tax)+$user_col+$rent));
        $annual_take_home = intval($income - (($income*$state_tax)+($income*$fed_tax)+($user_col*12)+($rent*12)));

        $fed_amount = $income * $fed_tax;
        $state_amount = $income * $state_tax;
        $adjusted_col = intval($user_col *10000);
          return $this->render('results.html.twig',
          [
            'rent'=>$rent,
            'state_name'=>$user_state,
            'income'=>$income,
            'col' => $adjusted_col,
            'fed' => $fed_amount,
            'state' => $state_amount,
            'mth' => $monthly_take_home,
            'ath' => $annual_take_home,
            'city'=>$city,
          ]);
        }
        return $this->render('default/index.html.twig',
        [
          'form' => $form ->createView(),
        ]);
      }
    }
