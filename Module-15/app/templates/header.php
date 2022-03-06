<?php if (! defined('PROJECT_CORE') || PROJECT_CORE !== true) {die;} ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="<?=PROJECT_PATH?>/app/assets/css/style.css">

    <title>Document</title>
</head>
<body>
    <header class="flex-block header">
        <div class="content-between-position">
            <ul class="header-menu list">
                <li><a href="#" class="link hover-blue">Test</a></li>
                <li><a href="#" class="link hover-blue">Test</a></li>
                <li><a href="#" class="link hover-blue">Test</a></li>
            </ul>
            <div class="header-feedback">
                <a href="tel:+79999999999" class="link phone">+7 (999) 999-99-99</a>
                <button class="button-tranperent button on-blue">Submit</button>
            </div>
        </div>
    </header>

    <main>
