<?php
function fetchHotelsFromAPI($prompt)
{
    $url = "https://connector.hub.austria.info/data/";
    $apiKey = "eyJhbGciOiJFQ0RILUVTK0EyNTZLVyIsImVuYyI6IkEyNTZHQ00iLCJlcGsiOnsia3R5IjoiRUMiLCJjcnYiOiJQLTI1NiIsIngiOiJYV0VJQmxtY1pONjg5MGpDWm1QaHpzNGJ1cmdIT2xRRU9JWE13VDNGTWN3IiwieSI6ImRXVmo3UGhQbUZ3NjR2cjg4bFdkd3ZEWENCd2p4Zk5Ibm9iaWpqSlF3ZkUifSwidHlwIjoiSldFIn0.OLdp3etGx0xE_spcQvFDI-hcIfmv90sV3nvsARApRFU_ws-4rtkjVA.0BUY1-6ejJHkyz8O.SFg23JfkMa7yC7IzdIEbS7b6j7NFVtLYEpYa5e4ung_LYOpYk0HqqTC1DZSkJlVD2tAO8h40ehsSDp_qapTk_Z0WLddkU3S3YyaX6JOWfjJWCC8rN_cLZ_UtvHj9w7ewX_rJ2uKAsgV7N5dzQo1cqKlUTSi3MeZXSxXxHpiuF8rm1VwgRPAHEV8-jXggbilPin03tIHgp0T6gBFCRhiOoJiPpi5n7yEk03M_1R1bx20IfYjj4flj1pLhHl6Gi-ic910veAC0UwtRf_s__WtnRu6KRwEuz1MEmvDwUBIUkCOIkS1EEX3YpCTP70kcDibmYK3wYlrqW3BygGVlKGm762c3M7OOd1JVd1IIwO1wzIzgsE9EL-C6p3xXZqupWAgVRWdHeKeOk44toNcfaf5LSLxYGU0uHnd4-lgmxjIi.7kQz7-_NMYKBW17w1DV8DQ:aHR0cDovL2NvbnRyb2xwbGFuZS5odWItOW00YTY1bHEuc3ZjLmNsdXN0ZXIubG9jYWwvaW50ZXJuYWwvYXBpL2RhdGFwbGFuZS9hY2Nlc3NJbmZv";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $apiKey",
        "Accept: application/json"
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    if (!$data || !isset($data['data'])) {
        return [];
    }

    $hotels = $data['data'];

    // Basic logic to simulate categories
    usort($hotels, function ($a, $b) {
        return ($a['price'] ?? 0) <=> ($b['price'] ?? 0);
    });

    $basic = $hotels[0] ?? null;
    $value = $hotels[(int)(count($hotels) / 2)] ?? null;
    $premium = end($hotels) ?: null;

    return [
        'basic' => $basic,
        'value' => $value,
        'premium' => $premium
    ];
}
?>

