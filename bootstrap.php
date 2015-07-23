<?php
/*
 * automatically loads the required class which is in wpWikiTags namespace
 * $classPath is the string which contains class name and napespace 
 * @param string $classPath
 * @return null
 */
spl_autoload_register(function ($classPath) {

    $sections = explode('\\', $classPath);
    $namespace = array_shift($sections);
    //we only autoload our own class with namespace wpWikiTags
    if ('wpWikiTags' !== $namespace) {
        return;
    }

    require __DIR__ . '/libs/' . implode($sections, '/') . '.php';
});

require_once __DIR__ . '/snippets.php';
