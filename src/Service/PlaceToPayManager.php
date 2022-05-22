<?php

namespace App\Service;

use Dnetix\Redirection\PlacetoPay;

class PlaceToPayManager
{

    public function __construct(private $placeToPayApi, private $placeToPayToken, private $placeToPayTranKey) { }

    public function getPlaceToPay(): PlacetoPay
    {
        return new PlacetoPay([
            'login' => $this->placeToPayToken, // Provided by PlacetoPay
            'tranKey' => $this->placeToPayTranKey, // Provided by PlacetoPay
            'baseUrl' => $this->placeToPayApi,
            'timeout' => 10, // (optional) 15 by default
        ]);
    }


}