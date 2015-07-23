<?php

spl_autoload_register(function ($classPath) {
    $sections = explode('\\', $classPath);
    $namespace = array_shift($sections);
    //we only autoload our own class with namespace wpWikiTags
    if ('wpWikiTags' !== $namespace) {
        return;
    }
    
    require __DIR__ . '/libs/' . implode($sections, '/') . '.php';
});