<?php
namespace App;

class Logger {
    public static function log($data){
        echo '<script>console.log(' . json_encode($data) . ')</script>';
    }
}