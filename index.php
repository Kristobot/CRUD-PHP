<?php

header('Content-Type: application/json');

require 'vendor/autoload.php';
require 'autoload.php';

use App\Models\Category;
use App\Models\Product;

//echo Product::show();
//echo Product::getAll();


//echo Category::update();
//echo Category::create();
//echo Category::delete();
echo Category::show();
//echo Category::getAll();