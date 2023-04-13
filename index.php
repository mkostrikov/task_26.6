<?php

require __DIR__ . '/HtmlFile.php';

$html = new HtmlFile(__DIR__ . '/file.html');
$html->removeTagByAttributeName('meta', ['keywords', 'description', 'title'], '');
