<?php

use \App\Models\Product;

const ADMIN = 'admin';
const CUSTOMER = 'customer';
const EMPLOYEE = 'employee';


//quote status
const DECLINED = 'Declined';
const ACTIVE = 'New';


function getDataApps($type)
{
    switch ($type) {
        case 'quoteStatus';
            return array('New', 'Declined');
            break;
        case 'products';
            return Product::orderBy('title', 'asc')
                ->get();
            break;
        default:
            return '';
    }
}
