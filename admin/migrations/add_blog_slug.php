<?php
/**
 * Adds `blogs.slug` (lowercase, unique) and fills it from each blog's title.
 * Safe to run more than once: only rows with an empty slug are filled.
 *
 * Local: set HTTP_HOST=localhost first, e.g.
 *   $env:HTTP_HOST='localhost'; php admin/admin/migrations/add_blog_slug.php
 * Live:  php admin/migrations/add_blog_slug.php
 */
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('CLI only');
}

include __DIR__ . '/../includes/connection.php';
include __DIR__ . '/../includes/functions.php';

$has_col = mysqli_num_rows(mysqli_query($con, "SHOW COLUMNS FROM `blogs` LIKE 'slug'"));
if (!$has_col) {
    mysqli_query($con, "ALTER TABLE `blogs` ADD `slug` VARCHAR(255) NULL DEFAULT NULL AFTER `title`") or exit(mysqli_error($con) . "\n");
    echo "Added column blogs.slug\n";
}

$rows = mysqli_query($con, "SELECT id, title FROM `blogs` WHERE `slug` IS NULL OR `slug` = '' ORDER BY id ASC");
$filled = 0;
while ($row = mysqli_fetch_assoc($rows)) {
    $base = make_slug($row['title']);
    if ($base === '') {
        $base = 'blog-' . $row['id'];
    }
    $slug = unique_blog_slug($con, $base, $row['id']);
    $stmt = mysqli_prepare($con, "UPDATE `blogs` SET `slug` = ? WHERE `id` = ?");
    mysqli_stmt_bind_param($stmt, 'si', $slug, $row['id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $filled++;
}
echo "Filled slug for $filled blog(s)\n";

$has_idx = mysqli_num_rows(mysqli_query($con, "SHOW INDEX FROM `blogs` WHERE Key_name = 'blogs_slug_unique'"));
if (!$has_idx) {
    mysqli_query($con, "ALTER TABLE `blogs` ADD UNIQUE KEY `blogs_slug_unique` (`slug`)") or exit(mysqli_error($con) . "\n");
    echo "Added unique index blogs_slug_unique\n";
}
echo "Done\n";
