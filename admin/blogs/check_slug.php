<?php
// Used by the jQuery Validate "remote" rule on blogs/add.php and blogs/edit.php.
// Returns JSON true when the slug is free, or an error message string when not.
include "../includes/authentication.php";
header('Content-Type: application/json');

$slug = make_slug($_GET['slug'] ?? '');
$id = (int) ($_GET['id'] ?? 0);

if ($slug === '') {
    echo json_encode('Slug may only contain letters, numbers and dashes.');
} elseif (blog_slug_exists($con, $slug, $id)) {
    echo json_encode('This slug is already used by another blog.');
} else {
    echo json_encode(true);
}
