<?php

function smarty_modifier_strip($text, $replace = ' ')
{
    return preg_replace('!\s+!', $replace, $text);
}

/* vim: set expandtab: */

?>
