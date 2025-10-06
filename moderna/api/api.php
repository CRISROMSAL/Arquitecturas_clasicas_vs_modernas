<?php
// api.php — REST JSON con CORS abierto para desarrollo local

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(204);
  exit;
}

function getData() {
    $json = '{
      "categorias": [
        {"slug": "cocinas", "nombre": "Cocinas"},
        {"slug": "banos", "nombre": "Ba\u00f1os"},
        {"slug": "electrohogar", "nombre": "Electrohogar"}
      ],
      "productos": [
        {"id": 1, "nombre": "Mueble Cocina Blanco", "precio": 799.99, "categoria_slug": "cocinas", "descripcion": "Cocina modular con acabado blanco mate."},
        {"id": 2, "nombre": "Encimera Granito", "precio": 299.00, "categoria_slug": "cocinas", "descripcion": "Encimera resistente de granito natural."},
        {"id": 3, "nombre": "Lavabo Suspendido", "precio": 159.50, "categoria_slug": "banos", "descripcion": "Lavabo con instalación suspendida y sifón oculto."},
        {"id": 4, "nombre": "Mampara Ducha 120", "precio": 220.00, "categoria_slug": "banos", "descripcion": "Mampara corredera templada 6mm."},
        {"id": 5, "nombre": "Lavadora 8kg A+++", "precio": 399.90, "categoria_slug": "electrohogar", "descripcion": "Lavadora eficiente con 15 programas."}
      ]
    }';
    return json_decode($json, true);
}

$data = getData();
$resource = $_GET['resource'] ?? 'categorias';

switch ($resource) {
  case 'categorias':
    echo json_encode($data['categorias'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    break;

  case 'productos':
    $categoria = $_GET['categoria_slug'] ?? null;
    $result = $data['productos'];
    if ($categoria) {
      $result = array_values(array_filter($result, fn($p) => $p['categoria_slug'] === $categoria));
    }
    echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    break;

  case 'producto':
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $found = null;
    foreach ($data['productos'] as $p) if ((int)$p['id'] === $id) { $found = $p; break; }
    if ($found) {
      echo json_encode($found, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } else {
      http_response_code(404);
      echo json_encode(["error" => "Producto no encontrado"]);
    }
    break;

  default:
    http_response_code(400);
    echo json_encode(["error": "Recurso no soportado"]);
}
