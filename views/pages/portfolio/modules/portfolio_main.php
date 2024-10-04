<section class="page-section portfolio" id="portfolio">
  <div class="container">
    <!-- Portfolio Section Heading-->
    <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Mis Proyectos</h2>
    <!-- Icon Divider-->
    <div class="divider-custom">
      <div class="divider-custom-line"></div>
      <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
      <div class="divider-custom-line"></div>
    </div>

    <!-- Portfolio Grid Items-->
    <div class="row justify-content-center">
      <?php
      // Conexión a la base de datos
      include $_SERVER['DOCUMENT_ROOT'] . '/freelance/database/db.php';

      // Consulta para obtener todos los proyectos del portafolio
      $stmt = $pdo->prepare("SELECT * FROM portfolio_items");
      $stmt->execute();
      $portfolio_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if ($portfolio_items) {
        // Mostrar cada proyecto del portafolio
        foreach ($portfolio_items as $item) {
          ?>
          <div class="col-md-6 col-lg-4 mb-5">
            <div class="portfolio-item mx-auto" data-bs-toggle="modal" href="#<?php echo 'modal' . $item['id']; ?>">
              <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                <div class="portfolio-item-caption-content text-center text-white">
                  <i class="fas fa-plus fa-3x"></i>
                </div>
              </div>
              <a class="portfolio-link" data-bs-toggle="modal" href="#<?php echo 'modal' . $item['id']; ?>">
                <img class="img-fluid" src="<?php echo $item['image']; ?>" alt="<?php echo htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?>" />
              </a>
            </div>
          </div>
          <?php
        }
      } else {
        echo "<p>No hay proyectos en el portafolio aún.</p>";
      }
      ?>
    </div>
  </div>
</section>

<!-- Modales para cada ítem del portafolio -->
<?php if ($portfolio_items): ?>
  <?php foreach ($portfolio_items as $item): ?>
    <div class="portfolio-modal modal fade" id="<?php echo 'modal' . $item['id']; ?>" tabindex="-1"
      aria-labelledby="<?php echo 'modal' . $item['id']; ?>Label" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header d-flex justify-content-center align-items-center">
            <h5 class="modal-title" id="<?php echo 'modal' . $item['id']; ?>Label"><?php echo htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?></h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="container">
              <div class="row justify-content-center">
                <div class="col-lg-8">
                  <!-- Controla el tamaño máximo de la imagen en el modal -->
                  <img class="img-fluid rounded mb-4 modal-img" src="<?php echo htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>"
                    alt="<?php echo htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?>" />
                  <p><?php echo htmlspecialchars($item['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                  <?php if (!empty($item['github'])): ?>
                    <a href="<?php echo htmlspecialchars($item['github'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="btn btn-dark">Ver en GitHub</a>
                  <?php endif; ?>
                  <?php if (!empty($item['web'])): ?>
                    <a href="<?php echo htmlspecialchars($item['web'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="btn btn-primary">Ver Proyecto</a>
                  <?php endif; ?>
                  <!-- Botón de cerrar ventana en rojo -->
                  <button class="btn btn-danger" data-bs-dismiss="modal" type="button">
                    <i class="fas fa-times"></i>
                    Cerrar ventana
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<style>
  /* Ajusta el tamaño de las imágenes del portafolio */
  .portfolio-item img {
      width: 100%;
      height: 300px; /* Ajusta la altura según lo que necesites */
      object-fit: cover; /* Asegura que la imagen se recorte pero mantenga el aspecto */
      border-radius: 5px; /* Añade un borde redondeado */
  }

  /* Controla el tamaño máximo de la imagen en el modal */
  .modal-img {
      max-width: 100%;
      max-height: 500px;
      object-fit: contain; /* Mantiene la proporción sin recortar la imagen */
  }

  /* Estilo del botón cerrar en rojo */
  .btn-danger {
      background-color: #dc3545;
      border-color: #dc3545;
      color: white;
  }

  .btn-danger:hover {
      background-color: #c82333;
      border-color: #bd2130;
  }
</style>
