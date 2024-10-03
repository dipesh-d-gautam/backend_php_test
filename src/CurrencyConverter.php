<?php

use GuzzleHttp\Client;

class CurrencyConverter
{
    // Function to convert price from EUR to GBP
    public function convertEurToGbp()
    {
        try {
            // Initialize CURL
            $ch = curl_init('https://api.currencylayer.com/live?access_key=fa8c2ec9e818ade5458553a4150b0367&currencies=GBP,EUR&source=USD&format=1');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Store the data
            $json = curl_exec($ch);
            curl_close($ch);

            // Decode JSON response
            $exchangeRates = json_decode($json, true);
            if (isset($exchangeRates['success']) && $exchangeRates['success'] === true) {
                // Get the exchange rates
                $eurToUsd = 1 / $exchangeRates['quotes']['USDEUR']; // EUR to USD
                $usdToGbp = $exchangeRates['quotes']['USDGBP']; // USD to GBP

                // Convert EUR to GBP using the rates
                $priceInGbp = $eurToUsd * $usdToGbp;

                return round($priceInGbp, 2);
            } else {
                // Handle any errors returned by the API
                return 'Error: ' . $exchangeRates['error']['info'];
            }
        } catch (Exception $error) {
            return 'Error: ' . $error->getMessage();
        }
    }
}
