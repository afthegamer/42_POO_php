<?php

/**
 * Autoloader PSR-4
 * Charge automatiquement les classes à partir de leur namespace et leur chemin.
 */
spl_autoload_register(function ($class) {
    // Définir le dossier racine pour les classes
    $baseDir = __DIR__ . '/src/';

    // Remplace les namespaces par des chemins (PSR-4)
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

    // Chemin complet du fichier
    $file = $baseDir . $classPath;

    // Inclut le fichier si trouvé
    if (file_exists($file)) {
        require_once $file;
    } else {
        die("La classe {$class} n'a pas pu être chargée depuis {$file}.");
    }
});
