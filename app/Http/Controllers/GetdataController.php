<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Panther\Client;

class GetdataController extends Controller
{
    public function index()
    {
        $url = 'https://www.sii.cl/servicios_online/1047-nomina_inst_financieras-1714.html';
        $client = Client::createChromeClient();
        $crawler = $client->request('GET', $url);
        $data['title'] = $crawler->filter('h2.title')->first()->text();
        $data['description'] = $crawler->filter('.contenido p[style="text-align:justify"]')->first()->text();
        $data['updatedAt'] = $crawler->filter('#fechaActualizacion')->first()->text();

        $crawler->filter('#tabledatasii thead tr')->each(function ($node) use (&$data) {
            $node->filter('th')->each(function ($n) use (&$data) {

                $data['table']['header'][] = $n->text();

            });

        });
        $i=0;
        $crawler->filter('#tabledatasii tbody tr')->each(function ($node) use (&$data,&$i) {
            $node->filter('td')->each(function ($n) use (&$data,$i) {
                $data['table']['data'][$i][] = $n->text();
            });
            $i++;
        });
        return response()->json($data);
    }
}
