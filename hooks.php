<?php
use dbproject\vue\VuePageHTMLBackOffice;

$app->hook('slim.before.dispatch', function () use ($app) {
    print VuePageHTMLBackOffice::header();
});

$app->hook('slim.after.dispatch', function () use ($app) {
    print VuePageHTMLBackOffice::getFooter();
});