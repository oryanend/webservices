<?php
$usuario = 'root';
$senha = '';
$database = 'maquina_academia';
$host = 'localhost';

$mysqli = new mysqli($host, $usuario, $senha, $database);

if($mysqli->error){
    die('Falha ao conectar com o BD: '.$mysqli->error);
}