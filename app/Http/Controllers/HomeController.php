<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://www.computrabajo.com.co/trabajo-de-cali');

        $jobs = [];

        $crawler->filter('#p_ofertas div.bClick')->each(function ($offer) use (&$jobs){

            $job = [];

            $job['id'] = $offer->attr('data-id');

            //print $node->text()."<br><br>";
            $h2 = $offer->filter('h2.tO')->first();

            //$title = $h2->text();
            $job['title']  = $h2->text();
            $job['url']  = $h2->filter('a')->first()->attr('href');
            //print $h2->text()."<br><br>";
            //print $url."<br><br>";

            $divTop = $offer->filter('div.w_100')->first();

            $job['company'] = $divTop->filter('a.it-blank')->first()->text();
            //print $company."<br><br>";

            $ratingElement = $divTop->filter('span.valoracions')->first();

            if($ratingElement->count() > 0){
                $job['rating'] = $ratingElement->text();
            }else{
                $job['rating'] = "Sin rating";
            }

            //print $rating."<br><br>"; 

            $locationElement = $divTop->children('span')->eq(1);
            $job['location'] = $locationElement->text();

            //print $location."<br><br>";

            //$description = $offer->filter('p')->first()->text();
            $job['description'] = $offer->filter('p')->first()->text();
            $job['time'] = $offer->filter('span.dO')->first()->text();

            //print $id."<br>".$time."<br>";

            $jobs[] = $job;

        });

        dd($jobs);

        //return view('home');
    }
}
