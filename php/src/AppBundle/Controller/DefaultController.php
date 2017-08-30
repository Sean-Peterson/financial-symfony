<?php

namespace AppBundle\Controller;

use AppBundle\Entity\City;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\UserInfoType;
use AppBundle\Form\CompareType;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function homeAction(Request $request)
    {
      return $this->render('default/index.html.twig');
    }
    /**
     * @Route("/single", name="single")
     */
    public function resultsAction(Request $request)
    {
      $db=[];
      $db['New-York']=['New-York','ny',1106,3006];
      $db['Portland']=['Portland','or',0.8118,1551];
      $db['Los-Angeles']=['Los-Angeles','ca',0.8228,1960];
      $db['Denver']=['Denver','co',0.8169,1577];
      $db['Austin']=['Austin','tx',0.7787,1574];
      $db['Nashville']=['Nashville','tn',0.7773,1478];
      $db['Seattle']=['Seattle','wa',0.9276,1875];
      $db['Washington']=['Washington','dc',0.943,2128];
      $db['San-Francisco']=['San-Francisco','ca',1.0146,3278];
      $db['Chicago']=['Chicago','il',0.8465,1802];
      $db['Boston']=['Boston','ma',0.9073,2442];
      $db['Minneapolis']=['Minneapolis','mn',0.8553,1349];
      $db['Dallas']=['Dallas','tx',0.6974,1229];
      $db['Houston']=['Houston','tx',0.7616,1356];
      $db['San-Jose']=['San-Jose','ca',0.8449,2414];
      $db['Indianapolis']=['Indianapolis','in',0.8078,1102];
      $db['Philadelphia']=['Philadelphia','pa',0.8743,1568];
      $db['San-Diego']=['San-Diego','ca',0.7908,1804];
      $db['Phoenix']=['Phoenix','az',0.7111,974];
      $db['Kansas-City']=['Kansas-City','mo',0.6883,909];
      $db['Miami']=['Miami','fl',0.9125,1879];
      $db['New-Orleans']=['New-Orleans','la',0.8199,1420];
      $db['Pittsburgh']=['Pittsburgh','pa',0.8173,1148];
      $db['Cleveland']=['Cleveland','oh',0.786,931];
      $db['Oklahoma-City']=['Oklahoma-City','ok',0.668,830];
      $db['Sacramento']=['Sacramento','ca',0.8236,1240];

      //creates an instance of the UserInfoType form and then handles the request
      $form = $this->createForm(UserInfoType::class);
      $form->handleRequest($request);
      //once the form is submitted then the if statment will be triggered
      if ($form->isSubmitted() && $form->isValid()) {
        //gets data from form
        $data = $form->getData();
        //next lines set a variable equal to data collected from the form or sets a new variable from hard coding.
        $income = $data['income'];
        $city = $data['city'];
        $marital_status = $data['marital_status'];
        $state='';
        $col = 0;
        $rent = 0;
        //finds the user specified city object from the repo and then gets all of it's information and sets it to variables
        foreach ($db as $key => $value) {
          if ($value[0] == $city) {
            $city_object=$db[$city];
          }
        }
        $state = $city_object[1];
        $col = $city_object[2];
        $rent = $city_object[3];
        //NY is bar for cost of living. So, all cities have a percentage point value that multiplies by NY col. Therefore I must always call NY and have the ability to read its values
        $ny = $db['New-York'];
        //this is the authorization that is required for the api to be valid
        $authorization = 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBUElfS0VZX01BTkFHRVIiLCJodHRwOi8vdGF4ZWUuaW8vdXNlcl9pZCI6IjU5MTlmMTlmZTkyMWMwMzY2NjZmMTMxZiIsImh0dHA6Ly90YXhlZS5pby9zY29wZXMiOlsiYXBpIl0sImlhdCI6MTQ5NDg3MjQ3OX0.grP0a9fG_4NdaOaRWk5H-lwG9XOfcgic8eUmhTPF7Tc';
        //sets up a curl to make an HTTP request (the api call)
        $curl = curl_init();
        //sets up api call with necessary information for the api call
        curl_setopt_array($curl, array(
          CURLOPT_RETURNTRANSFER => 1,
          CURLOPT_URL => 'https://taxee.io/api/v2/state/2017/'.$state,
          CURLOPT_USERAGENT => 'SeanCodes'
        ));
        curl_setopt($curl, CURLOPT_HTTPHEADER,array('Content-Type: application/json', $authorization));
        //executes api call
        $tax_info = curl_exec($curl);
        //closes api call
        curl_close($curl);
        //decodes information returned from api call into a json object
        $decoded = json_decode($tax_info, true);
        $state_tax = 0;
        //if the state has an income tax then the information will be set to variables and used later on. The for loop runs for as many brackets as there are for if the user is single or married.
        if (isset($decoded[$marital_status]['income_tax_brackets'])) {
          for($i=0;$i<count($decoded[$marital_status]['income_tax_brackets']);$i++){
            if(60000<$decoded[$marital_status]['income_tax_brackets'][$i]['bracket']){
              $tax_array_number=$i-1;
              break;
            }else{
              $tax_array_number=count($decoded[$marital_status]['income_tax_brackets'])-1;
            }
          }
          //get the state tax number and multiply by one onehundredth to get the correct decimal value.
          $state_tax=.01*((float)$decoded[$marital_status]['income_tax_brackets'][$tax_array_number]['marginal_rate']);
        }
        //same as above but for federal tax
        $authorization = 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBUElfS0VZX01BTkFHRVIiLCJodHRwOi8vdGF4ZWUuaW8vdXNlcl9pZCI6IjU5MTlmMTlmZTkyMWMwMzY2NjZmMTMxZiIsImh0dHA6Ly90YXhlZS5pby9zY29wZXMiOlsiYXBpIl0sImlhdCI6MTQ5NDg3MjQ3OX0.grP0a9fG_4NdaOaRWk5H-lwG9XOfcgic8eUmhTPF7Tc';
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://taxee.io/api/v2/federal/2017',
        CURLOPT_USERAGENT => 'SeanCodes'
        ));
        curl_setopt($curl, CURLOPT_HTTPHEADER,array('Content-Type: application/json', $authorization));
        $tax_info = curl_exec($curl);
        curl_close($curl);
        $decoded = json_decode($tax_info, true);
        for($i=0;$i<count($decoded[$marital_status]['income_tax_brackets']);$i++){
          if($income<$decoded[$marital_status]['income_tax_brackets'][$i]['bracket']){
            $tax_array_number=$i-1;
            break;
          }else{
            $tax_array_number=count($decoded[$marital_status]['income_tax_brackets'])-1;
          }
        }
        $fed_tax=.01*((float)$decoded[$marital_status]['income_tax_brackets'][$tax_array_number]['marginal_rate']);
        //if the city is not new york then multiply by new york to get the correct COL since ny is the base standard
        if ($city != "New-York") {
          $user_col = ($city_object[2]*.0001) * $ny[2];
          $rent = $city_object[3];
          $user_state = strtoupper($city_object[1]);
        }else{
          $user_col = $ny[2];
          $rent = $ny[3];
          $user_state = strtoupper($ny[1]);
        }
        //monthly take home is income minus monthly innescapable costs (rent, col, taxes)
        $monthly_take_home = intval($income/12 - ((($income/12)*$state_tax)+(($income/12)*$fed_tax)+$user_col+$rent));
        //annual take home is income minus monthly innescapable costs (rent, col, taxes)
        $annual_take_home = intval($income - (($income*$state_tax)+($income*$fed_tax)+($user_col*12)+($rent*12)));
        //gets federal tax
        $fed_amount = $income * $fed_tax;
        //gets state tax
        $state_amount = $income * $state_tax;
        //gets col in human readable form
        $adjusted_col = intval($user_col *10000);
          return $this->render('results.html.twig',
          [
            'rent'=>number_format($rent),
            'state_name'=>$user_state,
            'income'=>number_format($income),
            'col' => number_format($adjusted_col),
            'fed' => number_format($fed_amount),
            'state' => number_format($state_amount),
            'mth' => number_format($monthly_take_home),
            'ath' => number_format($annual_take_home),
            'city'=>$city,
            'rent_js'=>$rent,
            'income_js'=>$income,
            'col_js' => $adjusted_col,
            'fed_js' => $fed_amount,
            'state_js' => $state_amount,
            'mth_js' => $monthly_take_home,
            'ath_js' => $annual_take_home,
          ]);
        }
        return $this->render('single.html.twig',
        [
          'form' => $form ->createView(),
        ]);
      }
    }
