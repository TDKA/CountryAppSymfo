<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CountryController extends AbstractController
{

    /**
     * @Route("/country", name="country")
     */
    public function index(HttpClientInterface $httpClient, PaginatorInterface $paginator, Request $request): Response
    {


        $countries = $httpClient->request(
            "GET",
            "https://restcountries.eu/rest/v2/all"
        );


        $myCountries = $paginator->paginate(
            $countries->toArray(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );



        // dd($countries->toArray());


        return $this->render('country/index.html.twig', [
            'controller_name' => 'CountryController',
            'countries' => $myCountries
        ]);
    }
    /**
     * 
     * @Route("/country/show/{name}", name="showCountry")
     */
    public function show($name = null, HttpClientInterface $httpClient, Request $request)
    {
        //Search bar
        if (!$name) {

            $name = $request->get('search');
        }

        $country = $httpClient->request(
            "GET",
            "https://restcountries.eu/rest/v2/name/" . $name
        );

        $meteo = $httpClient->request(
            "GET",
            "http://api.openweathermap.org/data/2.5/weather?q=$name&appid=2a4f068d3a548645c2cbb707a034bc2b"
        );



        //Error if status 404 or 500
        $errorCountry = $country->getStatusCode();
        $errorMeteo = $meteo->getStatusCode();

        if ($errorCountry == 404 && $errorMeteo == 404  || $errorCountry == 500 && $errorMeteo == 500) {

            return $this->render('error/error404.html.twig', [

                'errorCountry' => $errorCountry,
                'errorMeteo' => $errorMeteo
            ]);
        }

        $theWeather = $meteo->toArray();
        // dd($theWeather);
        $theCountry = $country->toArray();

        return $this->render('country/show.html.twig', [

            'theCountry' => $theCountry[0],
            'theWeather' => $theWeather
        ]);
    }
}
