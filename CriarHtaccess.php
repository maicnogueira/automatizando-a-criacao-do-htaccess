<?php

class CriarHtaccess
{
    private $local;
    private $rewrite;
    private $ssl;
    private $url;

    public function __construct($ativarReescritaEHtaccess = 0, $ativarSsl = 0, $urlAmigavelVarURL = 0)
    {
        $this->local = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'];
        $this->rewrite = $ativarReescritaEHtaccess;
        $this->ssl = $ativarSsl;
        $this->url = $urlAmigavelVarURL;

    }

    public function ativarOuDesativar($criar = 0)
    {
        if ($criar === 0) {
            unlink($this->local . ".htaccess");
        } elseif ($criar === 1) {

            if (is_file($this->local . ".htaccess")) {
                unlink($this->local . ".htaccess");
            }

            $texto = "<Files .htaccess>\norder allow,deny\ndeny from all\n</Files>\n\n";
            $texto .= "RewriteEngine on\n";
            $texto .= $this->url === 1 ? "RewriteCond %{SCRIPT_FILENAME} !-f\nRewriteCond %{SCRIPT_FILENAME} !-d\nRewriteRule ^(.*)$ index.php?url=$1\n\n" : '';
            $texto .= $this->ssl === 1 ? "RewriteCond %{HTTPS} off\nRewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]\n" : "";

            file_put_contents($this->local . ".htaccess", $texto);
            header($_SERVER['PHP_SELF']);
        }
    }
}