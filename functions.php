<?php

include_once('log.php');

function createQuadri($prenom, $nom)
{
    return (strtoupper(substr($prenom, 0, 2)).strtoupper(substr($nom, 0, 2)));
}
