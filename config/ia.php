<?php
// config/ia.php
define("GEMINI_API_KEY", "apikey");
define("GEMINI_MODEL", "gemini-2.5-flash");
define("GEMINI_ENDPOINT", "https://generativelanguage.googleapis.com/v1beta/models/" . GEMINI_MODEL . ":generateContent");

if (
    GEMINI_API_KEY === "" ||
    GEMINI_API_KEY === "PEGA_AQUI_TU_CLAVE_DE_GEMINI"
) {
    die("Error: todavía no se configuró la clave de Gemini.");
}
