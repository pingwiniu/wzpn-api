<?php

header('Content-Type: text/html; charset=utf-8');

$url = "https://wielkopolskizpn.pl/box/ajax_league_map";

$data = array(
    'id' => '54515',
    'season' => '2023%2F2024'
);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

$response = curl_exec($ch);

curl_close($ch);

$doc = new DOMDocument();
$doc->loadHTML('<?xml encoding="utf-8" ?>' . $response);

$xpath = new DOMXPath($doc);
$tableRows = $xpath->query('//div[@class="results-screen__table-row cf"]');
$leagueItemDivs = $xpath->query('//div[contains(@class, "results-screen__table league-results__item slider-item")]');

$result = array();

foreach ($tableRows as $row) {
    $team1 = normalizeText($xpath->query('.//div[@class="results-screen__table-team-grid text-right"]', $row)->item(0)->textContent);
    $score = normalizeText($xpath->query('.//div[@class="results-screen__table-result-grid text-center"]', $row)->item(0)->textContent);
    $team2 = normalizeText($xpath->query('.//div[@class="results-screen__table-team-grid text-left"]', $row)->item(0)->textContent);

    $popupDiv = $xpath->query('.//span[contains(@class, "results-screen__table-popup uppercase has-popup")]', $row)->item(0);
    $popupText = array_map('normalizeText', explode("\n", $popupDiv->textContent));

    $hostTeam = $popupText[1];
    $matchTime = $popupText[2];
    $location = normalizeText($xpath->query('.//span[contains(@class, "color-dimgray")]', $popupDiv)->item(0)->textContent);

    if (strpos($team1, 'TEAM NAME') !== false || strpos($team2, 'TEAM NAME') !== false) {

        $scoreContainsDigit = preg_match('/\d/', $score);
        $score = $scoreContainsDigit ? $score : false;

        $row_data = array(
            'team1' => $team1,
            'score' => $score,
            'team2' => $team2,
            'hostTeam' => $hostTeam,
            'matchTime' => $matchTime,
            'location' => $location
        );

        $result[] = $row_data;
    }
}

$closestMatch = null;
$currentTime = time();
$closestTimeDiff = PHP_INT_MAX;
foreach ($result as $match) {
    $matchTimestamp = strtotime($match['matchTime']);
    $timeDiff = abs($matchTimestamp - $currentTime);
    if ($matchTimestamp > $currentTime && $timeDiff < $closestTimeDiff) {
        $closestMatch = $match;
        $closestTimeDiff = $timeDiff;
    }
}

if ($closestMatch !== null) {
    $closestMatchTime = $closestMatch['matchTime'];
    $closestMatchLeagueDiv = null;
    foreach ($leagueItemDivs as $div) {
        if (strpos($div->textContent, $closestMatchTime) !== false) {
            $closestMatchLeagueDiv = $div;
            break;
        }
    }

    if ($closestMatchLeagueDiv !== null) {
        $h5Element = $xpath->query('.//h5[contains(@class, "color-white")]', $closestMatchLeagueDiv)->item(0);
        if ($h5Element !== null) {
            $h5Text = normalizeText($h5Element->textContent);
            $closestMatch['kolejka'] = $h5Text;
        }
    }

    echo json_encode($closestMatch, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} else {
    echo "No matches found.";
}

function normalizeText($text) {
    return trim(preg_replace('/\s+/', ' ', $text));
}
?>
