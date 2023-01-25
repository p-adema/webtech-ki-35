<?php
define("PAGE", explode('.php', $_SERVER['SCRIPT_NAME'])[0]);
require_once "api_resolve.php";
require_once 'navbar.php';

/**
 * Start a page by initialising the session, creating a header and opening the body
 * @param string $title Tab title
 * @param string $description Meta-tag for page description
 * @param bool|string $styled Stylesheet links. On false: loads only global.css
 *                                              On true: loads global.css and {page}.css
 *                                              On string: loads global.css and {string}
 * @param bool|string $scripted Script links. On false: loads no scripts
 *                                            On true: loads global.js and {page}.js
 *                                            On string: loads global.js and {string}
 * @param string $extra Extra header elements put after all other tags
 * @return void Echoes to the page
 */
function html_header(string $title, string $description = '', bool $navbar = true, bool $authentication = false,
                     bool|string $styled = false, bool|string $scripted = false, string $extra = ''): void
{

    if ($scripted) {
        if ($scripted === 'ajax') {
            $script_tags = "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js\"></script>
                            <script src=\"/scripts/global.js\"></script>";
        } else {
            if ($scripted === true) {
                $script = PAGE . '.js';
            } else {
                $script = $scripted;
            }
            $script_tags = "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js\"></script>
                            <script src=\"/scripts/global.js\"></script>
                            <script src=\"/scripts$script\"></script>";
        }
    } else {
        $script_tags = '';
    }
    if ($styled) {
        if ($styled === true) {
            $style = PAGE . '.css';
        } else {
            $style = '/' . $styled;
        }
        $style_tag = "<link rel='stylesheet' href='/styles$style' type='text/css'/>";
    } else {
        $style_tag = '';
    }
    $html = "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='utf - 8'>
                <meta name='description' content=$description/>
                <title>$title</title>
                $script_tags
                <link rel=\"preconnect\" href=\"https://fonts.googleapis.com\" />
                <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin />
                <link href=\"https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap\" rel=\"stylesheet\" />
                <link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400..600,0..1,0\" />
                <link rel='stylesheet' href='/styles/global.css' type='text/css'/>
                $style_tag
                $extra
            </head>
            <body>";

    ensure_session();

    if (!$authentication) {
        $_SESSION['last-page'] = str_replace('index', '', PAGE);
    }

    echo $html;

    if ($navbar) {
        navbar();
    }
}


/**
 * Ends the HTML body and page
 * @return void Echoes to the page
 */
function html_footer(): void
{
    echo '  </body>
            </html>';
}

function auth_redirect(?string $if_auth = null, ?string $if_not_auth = null): void
{
    ensure_session();
    if ($if_auth !== null and $_SESSION['auth']) {
        header('Location: ' . $if_auth);
        exit;
    }
    if ($if_not_auth !== null and !$_SESSION['auth']) {
        $_SESSION['last-page'] = str_replace('index', '', PAGE);
        header('Location: ' . $if_not_auth);
        exit;
    }
}
