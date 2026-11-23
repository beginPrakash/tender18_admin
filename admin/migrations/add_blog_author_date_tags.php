<?php
/**
 * Blog authors, publish date and tags.
 *  - creates blog_authors (+ default "Tender18 Team"), blog_tags, blog_tag_relations
 *  - adds blogs.author_id and blogs.publish_date
 *  - existing blogs: author = default author, publish_date = DATE(created_at)
 * Safe to run more than once.
 *
 * Local: $env:HTTP_HOST='localhost'; php admin/admin/migrations/add_blog_author_date_tags.php
 * Live:  php admin/migrations/add_blog_author_date_tags.php
 */
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('CLI only');
}

include __DIR__ . '/../includes/connection.php';

function run($con, $sql, $label)
{
    if (!mysqli_query($con, $sql)) {
        exit("FAILED: $label\n" . mysqli_error($con) . "\n");
    }
    echo "OK: $label\n";
}

function has_column($con, $table, $col)
{
    return mysqli_num_rows(mysqli_query($con, "SHOW COLUMNS FROM `$table` LIKE '$col'")) > 0;
}

run($con, "CREATE TABLE IF NOT EXISTS `blog_authors` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(150) NOT NULL,
    `designation` VARCHAR(150) DEFAULT NULL,
    `bio` TEXT DEFAULT NULL,
    `status` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci", 'table blog_authors');

run($con, "CREATE TABLE IF NOT EXISTS `blog_tags` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(120) NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `blog_tags_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci", 'table blog_tags');

run($con, "CREATE TABLE IF NOT EXISTS `blog_tag_relations` (
    `blog_id` INT(11) NOT NULL,
    `tag_id` INT(11) NOT NULL,
    `sort_order` INT(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`blog_id`, `tag_id`),
    KEY `blog_tag_relations_tag` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci", 'table blog_tag_relations');

if (!has_column($con, 'blogs', 'author_id')) {
    run($con, "ALTER TABLE `blogs` ADD `author_id` INT(11) NULL DEFAULT NULL AFTER `slug`, ADD KEY `blogs_author_id` (`author_id`)", 'column blogs.author_id');
}
if (!has_column($con, 'blogs', 'publish_date')) {
    run($con, "ALTER TABLE `blogs` ADD `publish_date` DATE NULL DEFAULT NULL AFTER `author_id`, ADD KEY `blogs_publish_date` (`publish_date`)", 'column blogs.publish_date');
}

// Default author
$row = mysqli_fetch_assoc(mysqli_query($con, "SELECT id FROM `blog_authors` ORDER BY id ASC LIMIT 1"));
if ($row) {
    $default_id = (int) $row['id'];
} else {
    run($con, "INSERT INTO `blog_authors` (`name`, `designation`, `bio`, `status`) VALUES (
        'Tender18 Team',
        'Tender & GeM Experts',
        'Our team of tender consultants and GeM specialists shares practical guidance to help businesses find, bid and win government tenders.',
        1)", 'default author Tender18 Team');
    $default_id = (int) mysqli_insert_id($con);
}

run($con, "UPDATE `blogs` SET `author_id` = $default_id WHERE `author_id` IS NULL", 'backfill author_id');
run($con, "UPDATE `blogs` SET `publish_date` = DATE(`created_at`) WHERE `publish_date` IS NULL", 'backfill publish_date');

echo "Done\n";
