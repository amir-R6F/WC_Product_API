<?php

function sba_settings($key = '')
{

    $options = get_option('saba_settings');

    return isset($options[$key]) ? $options[$key] : null;
}


