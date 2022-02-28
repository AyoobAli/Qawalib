<?php

include "../qawalib.class.php";

$tp = new Qawalib();

$tp->setVerbose(4);

$tp->setTheme('default');
//$tp->setTheme('dark');

$tp->setLang('en');
//$tp->setLang('ar');

$tp->setVariable("title", 'Page Title');
$tp->setVariable("title", 'عنوان الصفحة', 'ar');

$tp->setVariable("lang_Dir", 'ltr');
$tp->setVariable("lang_align", 'left');
$tp->setVariable("lang_UserID", 'User ID');
$tp->setVariable("lang_UserName", 'User Name');
$tp->setVariable("lang_UserEmail", 'User Email');

$tp->setVariable("lang_Dir", 'rtl', 'ar');
$tp->setVariable("lang_align", 'right', 'ar');
$tp->setVariable("lang_UserID", 'رقم المستخدم', 'ar');
$tp->setVariable("lang_UserName", 'اسم المستخدم', 'ar');
$tp->setVariable("lang_UserEmail", 'بريد المستخدم', 'ar');

$tp->setVariable("UserData", [
    [
        "id" => '1',
        "name" => "Ayoob Ali",
        "email" => "name@example.com"
    ],
    [
        "id" => '2',
        "name" => "Salim Ali",
        "email" => "user@example.com"
    ]
]);

$tp->setVariable("UserData", [
    [
        "id" => '1',
        "name" => "ايوب علي",
        "email" => "name@example.com"
    ],
    [
        "id" => '2',
        "name" => "سالم احمد",
        "email" => "user@example.com"
    ]
], 'ar');


//$tp->printTemplate('header');
//$tp->render('content');
//$tp->printTemplate("body");

$tp->printTemplate("index");



?>