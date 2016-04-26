<?php
return array(
    'URL_ROUTE_RULES'   =>  array(
        '/^a_(\d+)$/'   => MODULE_NAME.'/Action/index?id=:1',
        '/^c_(\d+)$/'   => MODULE_NAME.'/Category/index?id=:1',
        ':id\d'         => MODULE_NAME.'/Goods/index'
    ),
);