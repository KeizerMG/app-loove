<?php
// Redirect function
function redirect($page) {
    header('location: ' . BASEURL . '/' . $page);
    exit;
}
?>
