<?php

return [
    'log' => true,
    'display' => true,
    /** List the errors type to be reported
     *  Default: E_ALL
     **/
    'reporting' => E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING,
];