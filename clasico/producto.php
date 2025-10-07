<?php
// producto.php — Ficha de producto ?id=...

function getData() {
    $json = '{
      "categorias": [
        {"slug": "cocinas", "nombre": "Cocinas"},
        {"slug": "banos", "nombre": "Baños"},
        {"slug": "electrohogar", "nombre": "Electrohogar"}
      ],
      "productos": [
        {"id": 1, "nombre": "Mueble Cocina Blanco", "precio": 799.99, "categoria_slug": "cocinas", "descripcion": "Cocina modular con acabado blanco mate." , "img": "https://img.global.news.samsung.com/co/wp-content/uploads/2022/03/AddWash4-1.jpg"},
        {"id": 2, "nombre": "Encimera Granito", "precio": 299.00, "categoria_slug": "cocinas", "descripcion": "Encimera resistente de granito natural." , "img": "https://img.global.news.samsung.com/co/wp-content/uploads/2022/03/AddWash4-1.jpg"},
        {"id": 3, "nombre": "Lavabo Suspendido", "precio": 159.50, "categoria_slug": "banos", "descripcion": "Lavabo con instalación suspendida y sifón oculto." , "img": "https://img.global.news.samsung.com/co/wp-content/uploads/2022/03/AddWash4-1.jpg"},
        {"id": 4, "nombre": "Mampara Ducha 120", "precio": 220.00, "categoria_slug": "banos", "descripcion": "Mampara corredera templada 6mm." , "img": "https://img.global.news.samsung.com/co/wp-content/uploads/2022/03/AddWash4-1.jpg"},
        {"id": 5, "nombre": "Lavadora 8kg A+++", "precio": 399.90, "categoria_slug": "electrohogar", "descripcion": "Lavadora eficiente con 15 programas." , "img": "https://img.global.news.samsung.com/co/wp-content/uploads/2022/03/AddWash4-1.jpg"}
      ]
    }';
    return json_decode($json, true);
}

function findProductById($productos, $id) {
    foreach ($productos as $p) {
        if ((int)$p['id'] === (int)$id) return $p;
    }
    return null;
}

function findCategoryName($categorias, $slug) {
    foreach ($categorias as $c) {
        if ($c['slug'] === $slug) return $c['nombre'];
    }
    return 'Sin categoría';
}

$data = getData();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$producto = findProductById($data['productos'], $id);
$categoriaNombre = $producto ? findCategoryName($data['categorias'], $producto['categoria_slug']) : '';

header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>
    <?php echo $producto ? htmlspecialchars($producto['nombre']) : 'Producto no encontrado'; ?>
  </title>
</head>
<body>
  <p><a href="index.php">← Volver al inicio</a></p>

  <?php if (!$producto): ?>
    <h1>Producto no encontrado</h1>
    <p>El producto solicitado no existe.</p>
  <?php else: ?>
    <h1><?php echo htmlspecialchars($producto['nombre']); ?></h1>

    <p> <img src= "<?php echo $producto["img"]; ?>"></img> </p>
    <p><strong>Precio:</strong> <?php echo number_format($producto['precio'], 2, ',', '.'); ?> €</p>
    <p><strong>Categoría:</strong>
      <a href="categoria.php?slug=<?php echo urlencode($producto['categoria_slug']); ?>">
        <?php echo htmlspecialchars($categoriaNombre); ?>
      </a>
    </p>
    <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
  <?php endif; ?>
</body>
</html>
