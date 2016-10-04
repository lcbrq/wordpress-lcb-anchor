<?php

/*
  Plugin Name: LCB Anchors
  Description: Automated anchors for post content
  Version: 1.0.0.
  Author: LeftCurlyBracket
  Author URI: http://leftcurlybracket.com
  License: GPLv2
 */

function create_anchor($atts, $content = null)
{

    $data = shortcode_atts(array(
        'id' => 'anchor',
            ), $atts);

    // Buffer output
    ob_start();

    echo '<a name="' . anchor_name_replace($data['id']) . '"></a>';

    return ob_get_clean();
}

add_shortcode('jumpto', 'create_anchor');

function list_anchors($atts = false, $content = null)
{

    global $post;
    $content = $post->post_content;

    $pattern = get_shortcode_regex();
    preg_match_all('/' . $pattern . '/s', $post->post_content, $matches); // check all shortcode

    $jumpers = array();

    foreach ($matches[0] as $match) {
        if (strpos($match, '[jumpto id=') !== false) {
            $match = str_replace('[jumpto id="', '', $match);
            $match = str_replace('"]', "", $match);
            $jumpers[] = $match;
        }
    }

    if (count($jumpers)) {
        // Buffer output
        ob_start();
        echo '
        <div class="jumpers">
        <small>Jump to:</small>
        <span>
        ';

        foreach ($jumpers as $jumper) {
            echo '<a class="krown-button light small" target="_self" href="#' . anchor_name_replace($jumper) . '">' . $jumper . '</a> ';
        }

        echo "</span></div>";
        return ob_get_clean();
    }
}

function anchor_name_replace($name)
{
    $name = preg_replace("/[^a-zA-Z0-9 ]/", "-", $name);
    $name = str_replace(' ', '', $name);
    return strtolower($name);
}

add_shortcode('jumplist', 'list_anchors');
