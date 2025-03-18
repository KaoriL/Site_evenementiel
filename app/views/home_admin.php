
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="public/assets/css/index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="public/assets/css/admin.css?v=<?php echo time(); ?>">
    <!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>



</head>

<body>
    <div class="background">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
        <div class="blob blob-4"></div>
    </div>
    <section class="dashboard-list">
        <ul>
            <h1>Deejay13</h1>
            <li><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grid"
                    viewBox="0 0 16 16">
                    <path
                        d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5z" />
                </svg><a href="">Tableau de bord</a></li>
            <li><a href="">Rendez-vous</a></li>
            <li><a href="">Les commentaires</a></li>
        </ul>

        <div class="dashboard">
            <header>
                <div class="left">
                    <h2>Welcome back !</h2>
                    <h1>Tableau de bord</h1>
                </div>
                <nav>
                    <a href="">Notification</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="index.php?action=logout"><i class="fa-solid fa-power-off"></i></a>
                    <?php else: ?>
                        <a href="index.php?action=login">Connexion</a>
                    <?php endif; ?>
                </nav>
            </header>
            <div class="calendrier">
            <div id="kt_docs_fullcalendar_selectable">
            </div>
            

            </div>

        </div>

    </section>

    <script src="dist/bundle.js"></script>

</body>

</html>