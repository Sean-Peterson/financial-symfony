<?php

namespace AppBundle\Controller;

use AppBundle\Entity\City;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\UserInfoType;
use AppBundle\Form\CompareType;
use Doctrine\ORM\EntityManagerInterface;


class CompareController extends Controller
{
    /**
     * @Route("/compare", name="compare")
     */
    public function compareAction(Request $request, EntityManagerInterface $em)
    {

      //create a repository full of the City objects
      $repo = $this->getDoctrine()->getRepository('AppBundle:City');
      //NY is bar for cost of living. So, all cities have a percentage point value that multiplies by NY col. Therefore I must always call NY and have the ability to read its values
      $ny = $repo->findOneByCity('New-York');
      //creates an instance of the UserInfoType form and then handles the request
      $form2 = $this->createForm(CompareType::class);
      $form2->handleRequest($request);
      //once the form is submitted then the if statment will be triggered
      if ($form2->isSubmitted() && $form2->isValid()) {
        //gets data from form
        $data = $form2->getData();
        //next lines set a variable equal to data collected from the form or sets a new variable from hard coding.
        $income_first = $data['income'];
        $city_first = $data['city'];
        $marital_status_first = $data['marital_status'];
        $income_second = $data['income_second'];
        $city_second = $data['city_second'];
        $marital_status_second = $data['marital_status_second'];
        //put each piece of information into an associative array so they can be more efficiently accessed
        $compare = ['income'=>[$income_first,$income_second],'city'=>[$city_first,$city_second],'marital_status'=>[$marital_status_first,$marital_status_second]];
        $results=[];
        //use a for loop to access one city at a time and gather the necessary information
        $count=(count($compare['income'])-1);
        for ($i=0;$i<=$count;$i++) {
          $city='';
          $state='';
          $col = 0;
          $rent = 0;
          $income = $compare['income'][$i];
          $city = $compare['city'][$i];
          $marital_status = $compare['marital_status'][$i];
          //finds the user specified city object from the repo and then gets all of it's information and sets it to variables
          $city_object = $repo->findOneByCity($compare['city'][$i]);
          $state = $city_object->getState();
          $col = $city_object->getCol();
          $rent = $city_object->getRent();

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
          if (isset($decoded[$compare['marital_status'][$i]]['income_tax_brackets'])) {
            for($k=0;$k<count($decoded[$marital_status]['income_tax_brackets']);$k++){
              if(60000<$decoded[$marital_status]['income_tax_brackets'][$k]['bracket']){
                $tax_array_number=$k-1;
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
          for($j=0;$j<count($decoded[$marital_status]['income_tax_brackets']);$j++){
            if($income<$decoded[$marital_status]['income_tax_brackets'][$j]['bracket']){
              $tax_array_number=$j-1;
              break;
            }else{
              $tax_array_number=count($decoded[$marital_status]['income_tax_brackets'])-1;
            }
          }
          $fed_tax=.01*((float)$decoded[$marital_status]['income_tax_brackets'][$tax_array_number]['marginal_rate']);
          //if the city is not new york then multiply by new york to get the correct COL since ny is the base standard
          if ($city != "New-York") {
            $user_col = ($city_object->getCol()*.0001) * $ny->getCol();
            $rent = $city_object->getRent();
            $user_state = strtoupper($city_object->getState());
          }else{
            $user_col = $ny->getCol();
            $rent = $ny->getRent();
            $user_state = strtoupper($ny->getState());
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

          $results['city'.$i]=$city;
          $results['state_name'.$i]=$user_state;
          $results['rent'.$i]=number_format($rent);
          $results['income'.$i]=number_format($income);
          $results['col'.$i]=number_format($adjusted_col);
          $results['fed'.$i]=number_format($fed_amount);
          $results['state'.$i]=number_format($state_amount);
          $results['mth'.$i]=number_format($monthly_take_home);
          $results['ath'.$i]=number_format($annual_take_home);
          $results['income_js'.$i]=$income;
          $results['rent_js'.$i]=$rent;
          $results['col_js'.$i]=$adjusted_col;
          $results['fed_js'.$i]=$fed_amount;
          $results['state_js'.$i]=$state_amount;
          $results['mth_js'.$i]=$monthly_take_home;
          $results['ath_js'.$i]=$annual_take_home;

        }
        if ($results['ath0']>$results['ath1']) {
          $results['percentage']=intval((($results['ath0']/$results['ath1'])-1)*100);
          $results['more_money']=$results['city0'];
          $results['less_money']=$results['city1'];
        }else{
          $results['percentage']=intval((($results['ath1']/$results['ath0'])-1)*100);
          $results['more_money']=$results['city1'];
          $results['less_money']=$results['city0'];
        }
          return $this->render('compare_results.html.twig',
          [
            'results' => $results,
          ]);
        }
        return $this->render('default/compare.html.twig',
        [
          'form2' => $form2 ->createView(),
        ]);
      }

    }
