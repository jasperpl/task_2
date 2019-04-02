<?php
include("functions.php");
$poprawny_temat=0;
$plik="tematy.txt";
$plik_2="wpisy.txt";
if(isset($_POST['submit']) and ($_POST["tekst"]!="") and ($_POST["tytul"]!="")and ($_POST["autor"]!=""))
{
    $wpisy = read($plik);
    $wpis = array(stripslashes($_POST['tytul']), date("Y-m-d H:i:s"), stripslashes($_POST['tekst']), stripslashes($_POST['autor']),(count($wpisy)+1));
    $wpisy[]=$wpis;
    write($wpisy, $plik);
    header( "Location: ".$_SERVER["PHP_SELF"] );
    exit;
}
if (isset($_POST['submit_2']) and ($_POST["tekst_2"] != "") and ($_POST["autor_2"] != "")) {
    $posty_user = read($plik_2);
    $post_user = array(stripslashes($_POST['autor_2']), stripslashes($_POST['tekst_2']), date("Y-m-d H:i:s"), $_GET['temat']);
    $posty_user[] = $post_user;
    write($posty_user, $plik_2);
    header("Location: " . basename($_SERVER['REQUEST_URI']));
    exit;
}
if (($_GET['command'])=='delete')
{
    $wpisy = read($plik_2);
    unset($wpisy[$_GET['id']]);
    write( $wpisy, $plik_2);
    header( "Location: "."?temat=".$_GET['temat']."");
    exit;
}
if(isset($_POST['submit_3']) and ($_POST["tekst_3"] != "") and ($_POST["autor_3"] != ""))
{
    $tab_posts=read($plik_2);
    $post_id=$_GET['id'];
    $new_wpis = array(stripslashes($_POST['autor_3']). " (edytowany)", stripslashes($_POST['tekst_3']), date("Y-m-d H:i:s"), $_GET['temat']);
    $tab_posts[$post_id] = $new_wpis;
    write($tab_posts, $plik_2);
    header("Location: " . "?temat=".$_GET['temat']."");
    exit;
}
?>
<!doctype html>
<html lang="pl">
<head>
    <title>FORUM</title>
    <link rel="icon" href="icon.png">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Shadows+Into+Light" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poor+Story" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Libre+Barcode+39+Text" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
</head>
<body>

<header id="header_main">
    <h1 class="logo"><a id="h1_link" href="index.php">FORUM</a></h1>
</header>
<nav class="nav_main">
    <ul class="menu">
        <li><a href="../index.php">HOME</a></li>
        <li><a href="../zadanie1/index.php">ZADANIE 1</a></li>
        <li><a href="index.php">ZADANIE 2</a></li>
        <li><a href="index.php">ZADANIE 3</a></li>
        <li><a href="index.php">ZADANIE 4</a></li>
        <li><a href="index.php">ZADANIE 5</a></li>
        <li><a href="index.php">ZADANIE 6</a></li>
        <li><a href="index.php">ZADANIE 7</a></li>
    </ul>
</nav>
<?php
$actual_ur=basename($_SERVER['REQUEST_URI']);
$wpisy = read($plik);
$posty = read($plik_2);
if(!empty($wpisy))
{
    $wpisy= assign_amount_of_answers_to_topic($posty,$wpisy);
    $post .= fill($wpisy);
}
if ($_GET['temat']==null)
{
    $poprawny_temat=0;
    if (!empty($wpisy)) {
        print
            "<section id='tematy'>
                    <div id='watki'>Wątki</div>
                    <div id='ostatnie'>Odpowiedzi</div>";
        print $post;
    }
    print "</section>
<section id=\"formularz\">
        <form action=\"index.php\" method=\"post\">
            <h2>ZAŁÓŻ SWÓJ TEMAT</h2>
            <input name=\"tytul\" type=\"text\" placeholder=\"Stwórz nowy temat\"><br>
            <input id=\"autor\" name=\"autor\" type=\"text\" placeholder=\"Twoje Imię\"><br>
            <textarea name=\"tekst\" placeholder=\"Opisz swój temat\"></textarea><br>
            <input type=\"submit\" name=\"submit\" id=\"submit\" value=\"WYŚLIJ\"><br>
        </form>
</section>";
}
if(!empty($wpisy)){
    foreach($wpisy as $key=>$wpis) {
        if ($_GET['temat'] == $key) {
            $posty_user = read($plik_2);
            $posty_user = assign_posts_to_topic($posty_user, $wpis[4]);
            $do_tylu = $key - 1;
            $do_przodu = $key + 1;
            $poprawny_temat = 1;
            if ($key==1 and (count($wpisy)==1))
            {
                print "<nav class='nav_tematy'>
                        <a id=\"key_1_1\" class=\"button_nav\" href=\"index.php\"'>LISTA TEMATÓW</a>
                   </nav>";
            }
            else if ($key == 1 and (count($wpisy)!=1)) {
                print "<nav class='nav_tematy'>
                        <a id=\"key_1_1\" class=\"button_nav\" href=\"index.php\"'>LISTA TEMATÓW</a>
                        <a id=\"key_1_2\" class=\"button_nav\" href=\"index.php?temat=" . $do_przodu . "\"'>NASTĘPNY TEMAT -></a>
                   </nav>";
            }
            else if ($wpis == end($wpisy)) {
                print "<nav class='nav_tematy'>
                        <a id=\"key_2_0\" class=\"button_nav\" href=\"index.php?temat=" . $do_tylu . "\"'><- POPRZEDNI TEMAT</a>
                        <a id=\"key_2_1\" class=\"button_nav\" href=\"index.php\"'>LISTA TEMATÓW</a>
                   </nav>";
            }
            else {
                print "<nav class='nav_tematy'>
                        <a id=\"key_3_0\" class=\"button_nav\" href=\"index.php?temat=" . $do_tylu . "\"'><- POPRZEDNI TEMAT</a>
                        <a id=\"key_3_1\" class=\"button_nav\" href=\"index.php\"'>LISTA TEMATÓW</a>
                        <a id=\"key_3_2\" class=\"button_nav\" href=\"index.php?temat=" . $do_przodu . "\"'>NASTĘPNY TEMAT -></a>
                  </nav>";
            }
            print "<article class=\"post_glowny\" nr=\"" . $key . "\" >
                        <div class='tytul_glowny'>Temat: " . htmlspecialchars($wpis[0]) . "</div>
                        <div class=\"tresc_posta_glownego\">" . nl2br(htmlspecialchars($wpis[2])) . "</div>
                        <footer class=\"footer_posta_glownego\">przez " . htmlspecialchars($wpis[3]) . " >> " . $wpis[1] . "</footer>
               </article>";
            if (!empty($posty_user)) {
                $post_u .= fill_2($posty_user, $_GET['temat']);
            }
            print "<section class='all_posts'>";
            if (!empty($posty_user)) {
                print $post_u;
            }
            if(($_GET['command'])=='edit')
            {
                $tab_posts=read($plik_2);
                $post_id=$_GET['id'];
                $odnosnik="?temat=".$_GET['temat']."&id=".$post_id."";
                $wpis_edit=$tab_posts[$post_id];
                print "</section>
    <section id=\"formularz\">
        <form action=$odnosnik method=\"post\">
        <h2>WYPOWIEDZ SIĘ!</h2>
        <input id=\"autor\" name=\"autor_3\" type=\"text\" placeholder=\"Twoje Imię\" value=$wpis_edit[0]><br>
        <textarea name=\"tekst_3\" placeholder=\"Twój post\">$wpis_edit[1]</textarea><br>
        <input type=\"submit\" name=\"submit_3\" id=\"submit_2\" value=\"WYŚLIJ\"><br>
        </form>
    </section>";
            }
            else {
                print "</section>
    <section id=\"formularz\">
        <form action=$actual_ur method=\"post\">
        <h2>WYPOWIEDZ SIĘ!</h2>
        <input id=\"autor\" name=\"autor_2\" type=\"text\" placeholder=\"Twoje Imię\"><br>
        <textarea name=\"tekst_2\" placeholder=\"Twój post\"></textarea><br>
        <input type=\"submit\" name=\"submit_2\" id=\"submit_2\" value=\"WYŚLIJ\"><br>
        </form>
    </section>";
            }
        }
    }
}
if($poprawny_temat==0 and $actual_ur != "index.php" and $_SERVER['REQUEST_URI']!="/131176/zadanie2/")
{
    print "<div class='frg'>BRAK TAKIEJ STRONY/TAKIEGO TEMATU</div>";
}
?>
<footer id="stopka"> Ostatni wpis na forum: <?php $posty_user=read($plik_2); if(!empty($posty_user)){$data=compare_date($posty_user);echo $data;}else{echo "-brak wpisów-";}?>
</footer>
</body>
</html>