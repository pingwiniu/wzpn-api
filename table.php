<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $ch = curl_init();

        $url = 'https://wielkopolskizpn.pl/box/ajax_league_map';
        $data = [
            'id' => $_GET['id'],
            'season' => '2023%2F2024'
        ];

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
        ];

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);

        curl_close($ch);

        $rawText = strip_tags($response);
        $rawText .= '_laststring';

        $startKeyword = 'P-ty.';
        $endKeyword = '_laststring';

        $startPos = strpos($rawText, $startKeyword);
        $endPos = strpos($rawText, $endKeyword);

        if ($startPos !== false && $endPos !== false) {
            $content = substr($rawText, $startPos + strlen($startKeyword), $endPos - $startPos - strlen($startKeyword));
            $formattedContent = preg_replace_callback('/(\b(?:[\p{L}\.]+|\d{4})+\b)(\s+)(?=\b(?:[\p{L}\.]+|\d{4})+\b)/u', function($matches) {
                return $matches[1] . '_';
            }, trim($content));

            $formattedContent = str_replace(". I", "._I", $formattedContent);
            $pattern = '/(\d{2})\s+([^\d\s]+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+:\d+)\s+(\d+)/';

            preg_match_all($pattern, $formattedContent, $matches, PREG_SET_ORDER);

            $teamsData = [];

            foreach ($matches as $match) {
                list(, $position, $teamName, $matchesPlayed, $won, $drawn, $lost, $goals, $points) = $match;
                list($goalsScored, $goalsConceded) = explode(":", $goals);

                $teamData = [
                    'position' => intval($position),
                    'matches_played' => intval($matchesPlayed),
                    'won' => intval($won),
                    'drawn' => intval($drawn),
                    'lost' => intval($lost),
                    'goals_scored' => intval($goalsScored),
                    'goals_conceded' => intval($goalsConceded),
                    'points' => intval($points),
                ];

                $teamsData[$teamName] = $teamData;
            }

            $teamsJson = json_encode($teamsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            if (!empty($teamsJson)) {
                header('Content-Type: application/json');
                echo $teamsJson;
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Content not found.']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid ID.']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Missing parameter: id']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
}
?>
