<?php
function geocodeCiudad($ciudad) {
    $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($ciudad);
    $respuesta = file_get_contents($url);
    $datos = json_decode($respuesta);
    return ($datos) ? ['lat' => $datos[0]['lat'], 'lon' => $datos[0]['lon']] : null;
}

function calcularDistancia($lat1, $lon1, $lat2, $lon2) {
    // Fórmula de Haversine
    $radioTierra = 6371;
    $dlat = deg2rad($lat2 - $lat1);
    $dlon = deg2rad($lon2 - $lon1);
    $a = sin($dlat/2) * sin($dlat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dlon/2) * sin($dlon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    return $radioTierra * $c;
}
?>