<?php

namespace FootyNext\Fpl\Utils;

use Exception;

class FantasyPremierLeagueGet
{
    private string $baseUrl = "https://fantasy.premierleague.com/api/";

    /**
     * Retrieve FPL Player Data
     * @return mixed
     */
    public function getFPlPlayerData(): mixed
    {

        $url = $this->baseUrl . "bootstrap-static/";
        $response = $this->makeRequest($url);
        return json_decode($response, true);
    }

    /**
     * Retrieve player-specific detailed data
     * @param $playerId
     * @return mixed
     */
    public function getIndividualPlayerData($playerId): mixed
    {

        $url = $this->baseUrl . "element-summary/" . $playerId . "/";
        $response = $this->makeRequest($url);
        return json_decode($response, true);
    }

    /**
     * Retrieve the summary/history data for a specific entry/team
     * @param $entryId
     * @return mixed
     */
    public function getEntryData($entryId): mixed
    {

        $url = $this->baseUrl . "entry/" . $entryId . "/history/";
        $response = $this->makeRequest($url);
        return json_decode($response, true);
    }

    /**
     * Retrieve the personal data for a specific entry/team
     * @param $entryId
     * @return mixed
     */
    public function getEntryPersonalData($entryId): mixed
    {
        $url = $this->baseUrl . "entry/" . $entryId . "/";
        $response = $this->makeRequest($url);
        return json_decode($response, true);
    }

    /**
     * Retrieve GW-by-GW data for a specific entry/team
     * @param $entryId
     * @param $numGWs
     * @param $startGW
     * @return array
     */
    public function getEntryGWsData($entryId, $numGWs, $startGW = 1): array
    {

        $gwData = [];
        for ($i = $startGW; $i <= $numGWs; $i++) {
            $url = $this->baseUrl . "entry/" . $entryId . "/event/" . $i . "/picks/";
            $response = $this->makeRequest($url);
            $gwData[] = json_decode($response, true);
        }
        return $gwData;
    }

    /**
     * Retrieve the transfer data for a specific entry/team
     * @param $entryId
     * @return mixed
     */
    public function getEntryTransfersData($entryId): mixed
    {
        $url = $this->baseUrl . "entry/" . $entryId . "/transfers/";
        $response = $this->makeRequest($url);
        return json_decode($response, true);
    }

    /**
     * Retrieve the fixtures data for the season
     * @return mixed
     */
    public function getFixturesData(): mixed
    {
        $url = $this->baseUrl . "fixtures/";
        $response = $this->makeRequest($url);
        return json_decode($response, true);
    }

    /**
     * Helper function to handle requests and errors
     * @param $url
     * @return false|string
     */
    private function makeRequest($url): false|string
    {

        $retry = true;
        while ($retry) {
            try {
                $response = file_get_contents($url);
                if ($http_response_header[0] != "HTTP/1.1 200 OK") {
                    throw new Exception("Response was code " . $http_response_header[0]);
                }
                $retry = false;
            } catch (Exception $e) {
                sleep(5);
                continue;
            }
        }
        return $response;
    }
}
