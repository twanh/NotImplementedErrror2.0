<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Meta Data -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title id="title"><?php echo $page_title; ?></title>
        <meta content="Twan Huiskens, Wessel Heereme, Kylian and Koen Snelten" name="Author">
        <meta content="Stratego, game, videogame, strategy, boardgame" name="Keywords">
        <meta content="This is stragego made with html, css, js and php" name="Description">

        <!-- Styles -->
        <link rel="preconnect" href="https://cdnjs.cloudflare.com">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/css/bootstrap.min.css" integrity="sha512-T584yQ/tdRR5QwOpfvDfVQUidzfgc2339Lc8uBDtcp/wYu80d7jwBgAxbyMh0a9YM9F8N3tdErpFI8iaGx6x5g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="css/styles.css">

        <!-- Scripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.min.js" integrity="sha512-UR25UO94eTnCVwjbXozyeVd6ZqpaAE9naiEUBK/A+QDbfSTQFhPGj5lOR6d8tsgbBk84Ggb5A3EkjsOgPRPcKA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script type="application/javascript" src="scripts/main.js"></script>
    </head>
    <body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow p-3 mb-5 bg-white rounded">
            <a class="navbar-brand" href="#">WP22</a>
            <ul class="navbar-nav mr-auto">
                <?php $active = $navigation['active']; ?>
                <?php foreach($navigation['items'] as $title => $url){
                    if ($title == $active){ ?>
                        <li class="nav-item active">
                            <a class="nav-link" href="<?= $url ?>"><?= $title ?></a>
                        </li>
                    <?php } else {?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $url ?>"><?= $title ?></a>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </nav>
    </header>

    
