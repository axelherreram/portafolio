<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /freelance/web/views/pages/login.php');
    exit();
}

// Conexión a la base de datos
include '../../database/db.php';

// Procesar el formulario de agregar ítem al portafolio
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_portfolio_item'])) {
    $title = $_POST['title'];
    $image = $_POST['image'];
    $description = $_POST['description'];
    $github = $_POST['github'];
    $web = $_POST['web'];
    $user_id = $_SESSION['user_id'];

    // Insertar el nuevo ítem en la base de datos
    $stmt = $pdo->prepare("INSERT INTO portfolio_items (title, image, description, github, web, user_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $image, $description, $github, $web, $user_id]);

    // Mostrar alerta de éxito
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: '¡Ítem de portafolio agregado exitosamente!',
            confirmButtonText: 'OK'
        });
    </script>";
}

// Eliminar un ítem del portafolio
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM portfolio_items WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Eliminado',
            text: '¡Ítem de portafolio eliminado exitosamente!',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'dashboard.php#portfolio';
        });
    </script>";
}

// Obtener todos los ítems del portafolio
$stmt = $pdo->prepare("SELECT * FROM portfolio_items WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$portfolio_items = $stmt->fetchAll();

// Obtener todos los mensajes de contacto
$stmt_contact = $pdo->prepare("SELECT * FROM contact_me");
$stmt_contact->execute();
$contacts = $stmt_contact->fetchAll();

// Contar mensajes recibidos y proyectos creados
$total_messages = count($contacts);
$total_projects = count($portfolio_items);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="/freelance/web/views/assets/Umg.ico" type="Umg.ico">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Estilos personalizados */
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        #wrapper {
            display: flex;
            min-height: 100vh; /* Asegura que el contenido ocupe toda la pantalla */
            transition: all 0.3s ease;
        }

        #sidebar-wrapper {
            width: 250px;
            background-color: #343a40;
            color: white;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        #sidebar-wrapper.collapsed {
            width: 80px;
        }

        #sidebar-wrapper.collapsed .list-group-item {
            text-align: center;
        }

        #sidebar-wrapper.collapsed .list-group-item i {
            margin-right: 0;
        }

        #sidebar-wrapper.collapsed .list-group-item span {
            display: none;
        }

        #page-content-wrapper {
            flex-grow: 1;
            padding: 20px;
            min-height: 100vh; /* Asegura que el contenido ocupe toda la pantalla */
        }

        .navbar {
            padding: 1rem;
        }

        .btn-toggle {
            background-color: #007bff;
            color: white;
            border: none;
        }

        .btn-logout {
            background-color: #dc3545;
            color: white;
            border: none;
            margin-left: 10px;
        }

        .btn-web {
            background-color: #28a745;
            color: white;
        }

        .chart-container {
            width: 50%;
            margin: auto;
            margin-top: 20px;
        }

        .table {
            width: 100%;
            margin-top: 20px;
            text-align: center;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .list-group-item i {
            margin-right: 10px;
        }

        .sidebar-heading .btn-toggle {
            padding: 0;
            background: none;
            color: white;
        }
    </style>
</head>
<body>
<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="border-end bg-dark" id="sidebar-wrapper">
        <div class="sidebar-heading text-white">
            <button class="btn-toggle" id="menu-toggle">☰</button>
        </div>
        <div class="list-group list-group-flush">
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#home">
                <i class="fas fa-home"></i> <span>Dashboard</span>
            </a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#portfolio">
                <i class="fas fa-briefcase"></i> <span>Portafolio</span>
            </a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#contact">
                <i class="fas fa-envelope"></i> <span>Correos</span>
            </a>
        </div>
    </div>

    <!-- Page content -->
    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="container-fluid">
                <a href="/freelance/web/" class="btn btn-web ms-auto">Ir a la web</a>
                <a href="/freelance/web/views/pages/logout.php" class="btn btn-logout">Cerrar sesión</a>
            </div>
        </nav>

        <div class="container-fluid" id="content">
            <div class="chart-container">
                <canvas id="myChart"></canvas>
            </div>
            
            <!-- Sección de Portafolio -->
            <div id="portfolio" class="section">
                <h1>Portafolio</h1>
                <p>Esta es la sección de tu portafolio.</p>
                <!-- Formulario para agregar nuevo ítem -->
                <form action="dashboard.php#portfolio" method="POST">
                    <div class="form-group">
                        <label for="title">Título</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="image">URL de la imagen</label>
                        <input type="text" name="image" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Descripción</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="github">URL del Github (opcional)</label>
                        <input type="text" name="github" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="web">URL del proyecto (opcional)</label>
                        <input type="text" name="web" class="form-control">
                    </div>
                    <button type="submit" name="add_portfolio_item" class="btn btn-primary">Agregar Portafolio</button>
                </form>

                <!-- Listado de ítems del portafolio -->
                <h2 class="mt-4">Tus proyectos</h2>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Imagen</th>
                        <th>Github</th>
                        <th>Web</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($portfolio_items as $item): ?>
                        <tr>
                            <td><?php echo $item['title']; ?></td>
                            <td><?php echo $item['description']; ?></td>
                            <td><img src="<?php echo $item['image']; ?>" width="50"></td>
                            <td><a href="<?php echo $item['github']; ?>" target="_blank">Github</a></td>
                            <td><a href="<?php echo $item['web']; ?>" target="_blank">Web</a></td>
                            <td>
                                <a href="dashboard.php?delete=<?php echo $item['id']; ?>" class="btn btn-danger">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Sección de Contacto -->
            <div id="contact" class="section" style="display: none;">
                <h1>Mensajes de Contacto</h1>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Mensaje</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contacts as $contact): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($contact['full_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($contact['email_address'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($contact['phone_number'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($contact['message'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo $contact['created_at']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Datos para los gráficos
    const totalMessages = <?php echo $total_messages; ?>;
    const totalProjects = <?php echo $total_projects; ?>;

    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Mensajes', 'Proyectos'],
            datasets: [{
                label: 'Totales',
                data: [totalMessages, totalProjects],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(54, 162, 235, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Alternar el menú de la barra lateral
    document.getElementById("menu-toggle").addEventListener("click", function () {
        document.getElementById("sidebar-wrapper").classList.toggle("collapsed");
    });

    // Cambiar entre secciones
    const sections = document.querySelectorAll('.section');
    document.querySelectorAll('.list-group-item').forEach(item => {
        item.addEventListener('click', function (event) {
            event.preventDefault();
            sections.forEach(section => section.style.display = 'none');
            const target = item.getAttribute('href').substring(1);
            const targetSection = document.getElementById(target);
            if (targetSection) {
                targetSection.style.display = 'block';
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
