<?php
/**
 * @license https://creativecommons.org/licenses/by-sa/3.0/de/ CC BY-SA
 * @author Wolf Wortmann <ww@elementcode.de>
 *
 * @var \Elementcode\Medium\Controller\Base $this
 * @var string $title
 * @var string $content
 */
?>
<!DOCTYPE html>
<html class="<?=$this->router->class . '--' . $this->router->method?>">
<head>
    <title>🚀 <?=( $title ? $title . ' | ' : '' )?>Elementcode Medium</title>
    <meta charset="UTF-8">
    <link href="<?=BASE_URL?>assets/css/normalize.css" rel="stylesheet">
    <link href="<?=BASE_URL?>assets/css/index.css" rel="stylesheet">
</head>
<body>
<header id="HeaderArea">
    <div class="ContentRow">
        <a href="<?=BASE_URL?>" id="Logo">🚀 MEDIUM</a>
        <nav id="MainNav">
            <ul>
                <li><a href="<?=BASE_URL?>">Home</a></li>
                <li><a href="<?=BASE_URL?>router">Router</a></li>
            </ul>
        </nav>
    </div>
</header>
<main id="ContentArea"><?=$content?></main>
<footer id="FooterArea">
    <div class="ContentRow"></div>
</footer>
</body>
</html>