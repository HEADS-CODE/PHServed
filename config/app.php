<?php

//Website address

$website_protocol = "http";

if (
    isset($_SERVER["HTTPS"]) &&
    $_SERVER["HTTPS"] != "off"
) {
    $website_protocol = "https";
}

$website_host = isset($_SERVER["HTTP_HOST"])
    ? $_SERVER["HTTP_HOST"]
    : "localhost";

$script_name = isset($_SERVER["SCRIPT_NAME"])
    ? $_SERVER["SCRIPT_NAME"]
    : "/PHServed/buyer/register.php";

$project_folder = dirname(dirname($script_name));
$project_folder = str_replace("\\", "/", $project_folder);

$base_url =
    $website_protocol .
    "://" .
    $website_host .
    rtrim($project_folder, "/");

?>