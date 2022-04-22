<?php

class Header {
/**
 * @param $page Page
 */
function __construct($page) {
    $this->page = $page;
    if ($page->settings->header_show_totals) {
        $t = $page->settings->table;
        $t_bans = $t['bans'];
        $t_mutes = $t['mutes'];
        $t_warnings = $t['warnings'];
        $t_kicks = $t['kicks'];
        try {
            $st = $page->conn->query("SELECT
            (SELECT COUNT(*) FROM $t_bans),
            (SELECT COUNT(*) FROM $t_mutes),
            (SELECT COUNT(*) FROM $t_warnings),
            (SELECT COUNT(*) FROM $t_kicks)");
            ($row = $st->fetch(PDO::FETCH_NUM)) or die('Failed to fetch row counts.');
            $st->closeCursor();
            $this->count = array(
                'bans.php'     => $row[0],
                'mutes.php'    => $row[1],
                'warnings.php' => $row[2],
                'kicks.php'    => $row[3],
            );
        } catch (PDOException $ex) {
            Settings::handle_error($page->settings, $ex);
        }
    }
}

function navbar($links) {
    echo '<ul class="nav navbar-nav">';
    foreach ($links as $page => $title) {
        $li = "li";
        $class = "";
        if ((substr($_SERVER['SCRIPT_NAME'], -strlen($page))) === $page) {
            $class .= "active";
        }
        $li .= " class=\"$class\"";

        if ($this->page->settings->header_show_totals && isset($this->count[$page])) {
            $title .= ' <span class="' . $this->page->settings->badge_classes . '">';
            $title .= $this->count[$page];
            $title .= "</span>";
        }
        echo "<$li><a class=\"nav-link\" href=\"$page\">$title</a></li>";
    }
    echo '</ul>';
}

function print_header() {
$settings = $this->page->settings;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="AxiumMC">
    <link href="<?php echo $this->page->autoversion('inc/img/favicon.ico'); ?>" rel="shortcut icon">
    <!-- CSS -->
    <link href="<?php echo $this->page->autoversion('inc/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo $this->page->autoversion('inc/css/glyphicons.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo $this->page->autoversion('inc/css/custom.css'); ?>" rel="stylesheet">
    <script type="text/javascript">
        function withjQuery(tries, f) {
            if (window.jQuery) f();
            else if (tries > 0) window.setTimeout(function () {
                withjQuery(tries - 1, f);
            }, 100);
        }
    </script>
</head>


<header class="navbar <?php echo $settings->navbar_classes; ?>" role="banner">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#litebans-navbar" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">AxiumMC </a>
          </div>
            <nav id="litebans-navbar" class="collapse navbar-collapse">
                <?php
                $this->navbar(array(
                    // "index.php"    => $this->page->t("title.index"),
                    "bans.php"     => $this->page->t("title.bans"),
                    "mutes.php"    => $this->page->t("title.mutes"),
                    "warnings.php" => $this->page->t("title.warnings"),
                    "kicks.php"    => $this->page->t("title.kicks"),
                ));
                ?>
            </nav>
        </div>
</header>
<?php
}
}
?>
