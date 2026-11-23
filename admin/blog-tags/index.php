<?php
include "../includes/authentication.php";
$pages = 'blog_tags';
include '../includes/header.php';
include '../blog-authors/_guard.php';

$list_url = ADMIN_URL . 'blog-tags/index.php';

// Delete tag (also removes it from blogs)
if (!empty($_GET['delete'])) {
    $del_id = (int) $_GET['delete'];
    mysqli_query($con, "DELETE FROM `blog_tag_relations` WHERE tag_id = $del_id");
    mysqli_query($con, "DELETE FROM `blog_tags` WHERE id = $del_id");
    $_SESSION['success'] = 'Tag deleted and removed from its blogs.';
    echo "<script>window.location.href='$list_url';</script>";
    exit;
}

// Rename tag
if (isset($_POST['save_tag'])) {
    $tag_id = (int) $_POST['tag_id'];
    $name = trim(preg_replace('/\s+/', ' ', strip_tags($_POST['name'] ?? '')));
    $slug = make_slug($name);
    if ($name === '' || $slug === '') {
        $_SESSION['error'] = 'Please enter a valid tag name.';
    } else {
        $stmt = mysqli_prepare($con, "SELECT id FROM `blog_tags` WHERE slug = ? AND id != ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 'si', $slug, $tag_id);
        mysqli_stmt_execute($stmt);
        if (mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))) {
            $_SESSION['error'] = 'A tag with this name already exists.';
        } else {
            $stmt = mysqli_prepare($con, "UPDATE `blog_tags` SET name = ?, slug = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, 'ssi', $name, $slug, $tag_id);
            mysqli_stmt_execute($stmt);
            $_SESSION['success'] = 'Tag updated.';
        }
    }
    echo "<script>window.location.href='$list_url';</script>";
    exit;
}

foreach (['success' => 'success', 'error' => 'danger'] as $key => $cls) {
    if (!empty($_SESSION[$key])) {
        echo '<div class="alert alert-' . $cls . ' alert-dismissible fade show mb-4" role="alert"><strong>' . htmlspecialchars($_SESSION[$key]) . '</strong>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        $_SESSION[$key] = '';
    }
}
$tags = mysqli_query($con, "SELECT t.*, (SELECT COUNT(*) FROM `blog_tag_relations` r WHERE r.tag_id = t.id) AS blog_count
                            FROM `blog_tags` t ORDER BY t.name ASC");
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Blog Tags</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <p class="text-muted mb-0">Tags are added on the blog Add / Edit page. Here you can rename or delete them.</p>
            </div>
            <div class="card-body">
                <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>SR No.</th>
                            <th>Tag</th>
                            <th>URL slug</th>
                            <th>Blogs</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; while ($row = mysqli_fetch_assoc($tags)) { ?>
                            <tr>
                                <th scope="row"><?php echo $i++; ?></th>
                                <td>
                                    <form method="post" class="d-flex gap-2 align-items-center">
                                        <input type="hidden" name="tag_id" value="<?php echo (int) $row['id']; ?>">
                                        <input type="text" name="name" class="form-control form-control-sm" style="max-width:260px" value="<?php echo htmlspecialchars($row['name']); ?>">
                                        <button type="submit" name="save_tag" class="btn btn-sm btn-primary">Save</button>
                                    </form>
                                </td>
                                <td><code><?php echo htmlspecialchars($row['slug']); ?></code></td>
                                <td><?php echo (int) $row['blog_count']; ?></td>
                                <td>
                                    <a href="<?php echo $list_url; ?>?delete=<?php echo (int) $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this tag? It will be removed from <?php echo (int) $row['blog_count']; ?> blog(s).');"><i class="ri-delete-bin-fill align-bottom"></i> Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
