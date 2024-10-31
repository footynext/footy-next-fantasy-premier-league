<?php

namespace FootyNext\Fpl\Utils;

use Exception;

class UnderstatDataFetcher
{
    /**
     * Fetch EPL team and player data from Understat.
     * @return array
     * @throws Exception
     */
    public function getEplData(): array
    {
        $scripts = $this->getData("https://understat.com/league/EPL/2024");
        $teamData = [];
        $playerData = [];

        foreach ($scripts as $script) {
            foreach ($script['contents'] as $content) {
                $splitData = explode('=', $content);
                $data = trim($splitData[0]);

                if ($data === 'var teamsData') {
                    preg_match('/JSON\.parse\(\'(.*)\'\)/', $splitData[1], $matches);
                    $decodedContent = hex2bin($matches[1]);
                    $teamData = json_decode($decodedContent, true);
                } elseif ($data === 'var playersData') {
                    preg_match('/JSON\.parse\(\'(.*)\'\)/', $splitData[1], $matches);
                    $decodedContent = hex2bin($matches[1]);
                    $playerData = json_decode($decodedContent, true);
                }
            }
        }

        return [$teamData, $playerData];
    }

    /**
     * Helper function to retrieve data from a URL.
     * @param string $url
     * @return array
     * @throws Exception
     */
    private function getData(string $url): array
    {
        $html = file_get_contents($url);
        if ($html === false) {
            throw new Exception("Failed to retrieve data from $url");
        }

        preg_match_all('/<script.*?>(.*?)<\/script>/s', $html, $matches);
        $scripts = [];

        foreach ($matches[1] as $scriptContent) {
            $scripts[] = ['contents' => [$scriptContent]];
        }

        return $scripts;
    }
}