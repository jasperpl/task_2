<?php
function fill($tab_post)
{
    if(is_array($tab_post))
    {
        foreach($tab_post as $key=>$wpis)
        {
            $post .= "<article class=\"temat\" nr=\"".$key."\" >
                        <div class=\"tresc\">
                        <a class=\"odnosnik_temat\" href=\"?temat=".$key."\">" .htmlspecialchars($wpis[0])."</a>
                        </div>
                        <div class=\"liczba_wpisow\">$wpis[5]</div>
                        <footer class=\"footer\">przez ".htmlspecialchars($wpis[3])." >> ".$wpis[1]."</footer>
                      </article>";
        }
    }
    return $post;
}

function fill_2($tab_post,$get_temat)
{
    if(is_array($tab_post))
    {
        foreach($tab_post as $key=>$wpis)
        {
            if($get_temat==$wpis[3]) {
                $post_u .= "<article class=\"post\" nr=\"" . $wpis[4] . "\" >
                            <div class=\"tresc_posta\">" . nl2br(htmlspecialchars($wpis[1])) . "</div>
                            <footer class=\"footer_posta\">przez " . htmlspecialchars($wpis[0]) . " >> " . $wpis[2] ." >> Odpowiedź nr: "
                            .$wpis[4]. "<a href=\"?temat=".$wpis[3]."&id=".$key."&command=edit\" id=\"submit_3\" 
                            type=\"submit\" name=\"submit_3\" value=\"Edytuj\">Edytuj</a><a href=\"?temat=".$wpis[3]."&id="
                            .$key."&command=delete\" id=\"submit_4\" type=\"submit\" name=\"submit_4\" value=\"Kasuj\">Kasuj</a> </footer>
                            </article>";
            }
        }
    }
    return $post_u;
}

function write($wpisy, $plik){
    $pk = fopen( $plik, "w" );
    if( $wpisy )
    {
        foreach( $wpisy as $wpis )
        {
            foreach($wpis as $key=>$value )
            {
                $wpis[$key]=base64_encode($value);
            }
            $post .= implode( ":", $wpis )."\n";
        }
    }
    else
    {
        $post = "";
    }
    fwrite($pk, $post );
    fclose($pk);
}

function read($plik)
{
    $zawartosc = file($plik);
    $x=0;
    if($zawartosc)
    {
        foreach( $zawartosc as $jeden_wpis )
        {
            $wpis = explode(':', trim($jeden_wpis) );
            foreach( $wpis as $key=>$value)
            {
                $wpis[$key]=base64_decode($value);
            }
            $x++;
            $tab[$x]=$wpis;
        }
    }
    else
    {
        $tab = NULL;
    }
    return $tab;
}

function assign_posts_to_topic($tab_of_posts, $topic_id)
{
    $id=1;
    foreach ($tab_of_posts as $key=>$post)
    {
        if($post[3]==$topic_id)
        {
            array_push($post,$id);
            $id++;
        }
        else
        {

        }
        $tab_of_posts[$key]=$post;

    }
    return $tab_of_posts;
}

function assign_amount_of_answers_to_topic($tab_of_posts, $tab_of_topics)
{
    foreach ($tab_of_topics as $key=>$topic)
    {
        $counter=0;
        foreach ($tab_of_posts as $k=>$post)
        {
            if($topic[4]==$post[3])
            {
                $counter++;
            }
            else
            {

            }
        }
        array_push($topic,$counter);
        $tab_of_topics[$key]=$topic;
    }
    return $tab_of_topics;
}
function compare_date($tab_of_posts)
{
    foreach($tab_of_posts as $key=>$value)
    {
        $post=$tab_of_posts[$key];
        $tab_of_dates[]=$post[2];
    }
    date_default_timezone_set('UTC');
    $max_date=gmdate("Y-m-d H:i:s",max(array_map('strtotime',$tab_of_dates)));
    return $max_date;
}
?>