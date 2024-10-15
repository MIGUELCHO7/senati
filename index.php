<?php
$db_server = 'localhost';
$db_username = 'root';
$db_password = '';
$db_database = 'music';
$db_connect = new mysqli($db_server, $db_username, $db_password, $db_database);
if ($db_connect->connect_error) {
    die('Conexión fallida: ' . $db_connect->connect_error);
}
$db_connect->query("SET NAMES 'utf8'");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>-- Sistema ventas --</title>
    <link rel="stylesheet" href="css/estilo1.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .whatsapp-logo {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            z-index: 100;
        }
    </style>
</head>
<body>
    <header>
        <img src="img/img1.jpg" alt="Logo">
        <label>BIENVENIDOS A LLENGUI MUSIC </label>
        <form action="php/buscar.php" method="get" class="buscador">
            <input type="text" name="query" placeholder="Buscar productos..." class="input-busqueda">
            <button type="submit" class="boton-busqueda">🔍</button>
        </form>
        <a href="inicio.php" class="boton">🚹Iniciar Sesión</a>
    </header>
    <nav>
        <a href="index.php" class="boton">Inicio</a>
        <a href="php/numeros.php" class="boton">Contáctenos</a>
        <a href="php/categoria.php" class="boton">Categoría</a>
        <a href="php/producto.php" class="boton">Productos</a>
        <a href="php/carrito.php" class="boton">Carrito 🛒</a>
        <a href="php/redes.php" class="boton">Síguenos en redes sociales</a>
    </nav>
    <section class="slider">
        <div class="slider-container">
            <div class="slider-slide"><img src="img/slide1.jpeg" alt="Imagen 1"></div>
            <div class="slider-slide"><img src="img/slide2.jpeg" alt="Imagen 2"></div>
            <div class="slider-slide"><img src="img/slide3.jpeg" alt="Imagen 3"></div>
            <div class="slider-slide"><img src="img/slide4.jpeg" alt="Imagen 4"></div>
            <div class="slider-slide"><img src="img/slide5.jpg" alt="Imagen 5"></div>
            <div class="slider-slide"><img src="img/slide6.jpg" alt="Imagen 6"></div>
            <div class="slider-slide"><img src="img/slide7.jpg" alt="Imagen 7"></div>
            <div class="slider-slide"><img src="img/slide8.jpg" alt="Imagen 8"></div>
        </div>
        <button class="prev">&#10094;</button>
        <button class="next">&#10095;</button>
        <script>
           const prevButton = document.querySelector('.prev');
            const nextButton = document.querySelector('.next');
            const slides = document.querySelectorAll('.slider-slide');
            let index = 0;
            
            function showSlide(n) {
                if (n >= slides.length) {
                    index = 0;
                } else if (n < 0) {
                    index = slides.length - 1;
                } else {
                    index = n;
                }
                document.querySelector('.slider-container').style.transform = `translateX(-${index * 100}%)`;
            }
            prevButton.addEventListener('click', () => showSlide(index - 1));
            nextButton.addEventListener('click', () => showSlide(index + 1));
            setInterval(() => showSlide(index + 1), 5000);
        </script>
         <a href="https://web.whatsapp.com/" target="_blank">
        <img src="img/img9.jpg" alt="WhatsApp" class="whatsapp-logo">
    </a>
    </section><br>

    <section class="productos-container">
    <h1>PROMOCIONES Y OFERTAS</h1><br>
    <ul class="promo-list">
        <?php
        // Consulta para obtener las promociones desde la base de datos
        $result = $db_connect->query("SELECT nombre, descripcion, fecha_inicio, fecha_fin, descuento, imagen FROM promociones");
        if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()): ?>
                <li class="promo-item">
                    <div class="promo-content">
                        <?php 
                        if (!empty($row['imagen'])): 
                            echo '<img class="promo-img" src="php/' . $row['imagen'] . '" alt="' . $row['nombre'] . '">';
                        else: 
                            echo '<img class="promo-img" src="default-image.jpg" alt="Imagen no disponible">'; // Imagen por defecto
                        endif;
                        ?>
                        <h2><?php echo $row['nombre']; ?></h2>
                        <p><?php echo $row['descripcion']; ?></p>
                        <p><strong>Inicio:</strong> <?php echo date('d/m/Y', strtotime($row['fecha_inicio'])); ?></p> <!-- Formato de fecha mejorado -->
                        <p><strong>Fin:</strong> <?php echo date('d/m/Y', strtotime($row['fecha_fin'])); ?></p>
                        <p><strong>Descuento:</strong> <?php echo $row['descuento']; ?>%</p>
                        <button class="button-ver-mas">Ver más</button> <!-- Clase añadida -->
                    </div>
                </li>
            <?php endwhile;
        else: ?>
            <p>No hay promociones disponibles en este momento.</p>
        <?php endif; ?>
    </ul>
</section>


<section class="nuevos-productos-container">
    <h1>PRODUCTOS NUEVOS</h1>
    <ul class="nuevos-productos-list">
    <?php
    // Verificar conexión a la base de datos
    if (!$db_connect) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // Consulta a la base de datos para obtener los productos
    $stmt = $db_connect->prepare("SELECT id, nombre, descripcion, precio, imagen FROM productos");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = htmlspecialchars($row['id']);
            $nombre = htmlspecialchars($row['nombre']);
            $descripcion = htmlspecialchars($row['descripcion']);
            $precio = htmlspecialchars($row['precio']);
            $imagen = htmlspecialchars($row['imagen']);
            
            // Verificar si la imagen existe o usar una por defecto
            $imagePath = (file_exists("php/$imagen") && !empty($imagen)) ? "php/$imagen" : "php/imagenes/default.png";

            echo "<li class='producto-item'>
                    <a href='php/producto.php?id=$id'>
                        <img src='$imagePath' alt='$nombre'>
                    </a>
                    <h3>$nombre</h3>
                    <p>$descripcion</p>
                    <p>Precio: $$precio</p>
                    <form action='php/carrito.php' method='POST'>
                        <input type='hidden' name='producto_id' value='$id'>
                        <button type='submit' class='button'>Añadir al carrito</button>
                    </form>
                    <a href='php/producto.php?id=$id' class='button'>Ver más</a>
                  </li>";
        }
    } else {
        echo "<p>No se encontraron productos disponibles.</p>";
    }
    ?>
    </ul>
</section>


   <footer>
    <div class="footer-content">
        <div class="footer-section about">
        <h2>Sobre Nosotros</h2>
            <p>En <strong>LLENGUI MUSIC E.I.R.L.</strong>, somos una empresa apasionada por la música. Nos comprometemos a ofrecer una amplia gama de instrumentos y equipos musicales de alta calidad para profesionales, aficionados y amantes del sonido.</p>
            <p>Desde nuestros inicios, hemos trabajado para acercar la música a todos. Con años de experiencia, nos hemos consolidado como referentes en la venta de productos musicales de marcas reconocidas.</p>
            <p>Nuestra misión es transformar vidas a través de la música, brindando asesoría especializada y productos innovadores.</p>
        </div>
        <!-- Redes sociales -->
        <div class="footer-section redes-sociales">
            <div class="container">
                <ul>
                    <li>
                        <a href="https://www.facebook.com/tu-perfil" target="_blank">
                            <i class="fab fa-facebook"></i>facebook
                        </a>
                    </li>
                    <li>
                        <a href="https://www.instagram.com/tu-perfil" target="_blank">
                            <i class="fab fa-instagram"></i> instagram
                        </a>
                    </li>
                    <li>
                        <a href="https://www.tiktok.com/@sigma117_ma?is_from_webapp=1&sender_device=pc" target="_blank">
                            <i class="fab fa-tiktok"></i> tiktok
                        </a>
                    </li>
                    <li>
                        <a href="https://t.me/tu-usuario" target="_blank">
                            <i class="fab fa-telegram"></i> telegram
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>
</body>
</html>