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
    <section class="flex">
        <div class="menu-container">
            <div class="header">
                <h4 class="brand">DEEEJAY13</h4>
                <label class="hamburger">
                    <input type="checkbox" id="menu-toggle">
                    <svg viewBox="0 0 32 32">
                        <path class="line line-top-bottom"
                            d="M27 10 13 10C10.8 10 9 8.2 9 6 9 3.5 10.8 2 13 2 15.2 2 17 3.8 17 6L17 26C17 28.2 18.8 30 21 30 23.2 30 25 28.2 25 26 25 23.8 23.2 22 21 22L7 22">
                        </path>
                        <path class="line" d="M7 16 27 16"></path>
                    </svg>
                </label>
            </div>
            <ul class="nav-links" id="menu">
                <li>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-grid" viewBox="0 0 16 16">
                        <path d="M1 2.5A1.5 ... (le reste inchangé)" />
                    </svg>
                    <a href="#">Tableau de bord</a>
                </li>
            </ul>
        </div>


        <div class="dashboard p-50 p-10">
            <header>
                <div class="left">
                    <p>Welcome back !</p>
                    <h1>Tableau de bord</h1>
                </div>
                <nav class="flex gap row">
                    <div class="notif-container">
                        <i class="fa-solid fa-bell notif-icon" id="notifIcon">
                            <span class="badge" id="notifBadge">0</span>
                        </i>
                        <div class="notif-menu" id="notifMenu">
                            <p id="notifContent">Aucune notification en attente</p>
                        </div>
                    </div>
                    <div class="flex row bg-white2 around p-10 align-center gap bor-radius10 h-20">
                        <i class="fa-solid fa-calendar" style="color: #000000;"></i>
                        <p class="c-noir" id="dateDisplay"></p>
                    </div>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a class="bg-white2 text-center p-10 align-center bor-radius10 h-20 flex c-noir"
                            href="index.php?action=logout"><i class="fa-solid fa-power-off"></i></a>
                    <?php else: ?>
                        <a href="index.php?action=login">Connexion</>
                        <?php endif; ?>
                </nav>
            </header>

            <div class="flex gap50 ">
                <div class="flex column gap w30">
                    <div class="flex row p-20 gap bg-white2 around">
                        <img class="img-boss" src="public/assets/image/img_boss.png" alt="">
                        <div class="flex column  ">
                            <h3>DEEJAY13</h3>
                            <p>Admin</p>
                        </div>
                        <div class="info text-center flex align-center center" href="">
                            <i class="fa-solid fa-ellipsis-vertical" id="openModalBtn" style="cursor: pointer;"></i>

                            <!-- Pop-up Modal -->
                            <div id="adminModal" class="modal">
                                <div class="modal-content">
                                    <span class="close">&times;</span>
                                    <h2>Informations Admin</h2>
                                    <p><strong>Email :</strong> <span id="adminEmail">...</span></p>

                                    <button id="changeEmailBtn">Changer Email</button>
                                    <button id="changePasswordBtn">Changer Mot de Passe</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex column p-20 comments gap">
                        <h3>Commentaires à valider</h3>
                        <p id="pending-comments-count"></p>
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment-box flex column gap p-20">
                                <div class="en-tete flex row gap border-box align-center">
                                    <h4>
                                        <?= htmlspecialchars($comment['username']) ?>
                                    </h4>
                                    <p class="stars">
                                        <?= str_repeat('★', $comment['rating']) . str_repeat('☆', 5 - $comment['rating']) ?>
                                    </p>
                                </div>
                                <div class="centre_comment flex row between gap ">
                                    <p class="comment-text"
                                        data-full-text="<?= nl2br(htmlspecialchars($comment['comment'])) ?>">
                                        <!-- Le JS va gérer l'affichage du "Voir plus" ici -->
                                    </p>
                                    <!-- Conteneur pour image et vidéo qui passe à la ligne si les deux sont présents -->

                                </div>
                                <div class="media-container ">
                                    <?php if (!empty($comment['image'])): ?>
                                        <div class="media-single">
                                            <img src="<?= htmlspecialchars($comment['image']) ?>" alt="Image du commentaire">
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($comment['video'])): ?>
                                        <div class="media-single">
                                            <video autoplay>
                                                <source src="<?= htmlspecialchars($comment['video']) ?>" type="video/mp4">
                                            </video>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex row gap"><button class="approve-btn"
                                        data-id="<?= $comment['id'] ?>">Approuver</button>
                                    <button class="delete-btn" data-id="<?= $comment['id'] ?>">Supprimer</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <!-- Conteneur pour afficher l'image en grand -->
                        <!-- Conteneur pour afficher l'image en grand -->
                        <div id="image-modal" class="modal" style="display: none;">
                            <span class="close" id="closeModal">&times;</span>
                            <img class="modal-content" id="modal-img" src="" style="display: none;">
                            <video class="modal-content" id="modal-video" style="display: none;" controls></video>
                        </div>
                    </div>
                </div>
                <div class="calendrier">
                    <div id="kt_docs_fullcalendar_selectable">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="dist/bundle.js">
    </script>
</body>

</html>