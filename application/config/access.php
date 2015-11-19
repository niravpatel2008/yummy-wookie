<?php
global $access;
$access = array
(
    "category" => array
        (
            "index" => array("a","m"),
            "ajax_list" => array("a","m"),
            "add" => array("a","m"),
            "edit" => array("a","m"),
            "delete" => array("a","m")
        ),

    "customer" => array
        (
            "index" => array("a","m"),
            "autocomplete" => array("a","m"),
            "ajax_list" => array("a","m"),
            "add" => array("a","m"),
            "edit" => array("a","m"),
            "delete" => array("a","m"),
        ),

    "dashboard" => array
        (
            "index" => array("a","m"),
            "deal_list" => array("a","m"),
        ),

    "dealer" => array
        (
            "index" => array("a","m"),
            "ajax_list" => array("a","m"),
            "add" => array("a","m"),
            "edit" => array("a","m"),
            "delete" => array("a","m"),
        ),

    "invoice" => array
        (
            "index" => array("a","m"),
            "getinvoice" => array("a","m"),
            "printinvoice" => array("a","m"),
            "ajax_list" => array("a","m"),
            "add" => array("a","m"),
            "edit" => array("a","m"),
            "delete" => array("a","m"),
            "deleteOrder" => array("a","m"),
        ),

    "product" => array
        (
            "index" => array("a","m"),
            "autocomplete" => array("a","m"),
            "ajax_list" => array("a","m"),
            "add" => array("a","m"),
            "edit" => array("a","m"),
            "delete" => array("a","m"),
        ),

    "purchase" => array
        (
            "index" => array("a","m"),
            "ajax_list" => array("a","m"),
            "add" => array("a","m"),
            "edit" => array("a","m"),
            "delete" => array("a","m"),
        ),

    "takein" => array
        (
            "index" => array("a","m","d"),
            "ajax_list" => array("a","m","d"),
            "add" => array("a","m","d"),
            "edit" => array("a","m"),
            "delete" => array("a","m"),
            "updateStatus" => array("a","m"),
        ),

    "users" => array
        (
            "index" => array("a","m"),
            "ajax_list" => array("a","m"),
            "add" => array("a","m"),
            "edit" => array("a","m"),
            "delete" => array("a","m"),
        )
);
?>