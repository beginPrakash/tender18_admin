<?php
include "../includes/authentication.php";
$pages = 'blog_authors';
include '../includes/header.php';
include '_guard.php';

// Delete (only when no blog uses the author)
if (!empty($_GET['delete'])) {
    $del_id = (int) $_GET['delete'];
    $used = (int) mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) c FROM `blogs` WHERE author_id = $del_id"))['c'];
    if ($used > 0) {
        $_SESSION['error'] = "This author is assigned to $used blog(s), so it can't be deleted. Set it to Inactive instead.";
    } else {
        mysqli_query($con, "DELETE FROM `blog_authors` WHERE id = $del_id");
        $_SESSION['success'] = 'Author deleted.';
    }
    echo "<script>window.location.href='" . ADMIN_URL . "blog-authors/index.php';</script>";
    exit;
}

foreach (['success' => 'success', 'error' => 'danger'] as $key => $cls) {
    if (!empty($_SESSION[$key])) {
        echo '<div class="alert alert-' . $cls . ' alert-dismissible fade show mb-4" role="alert"><strong>' . htmlspecialchars($_SESSION[$key]) . '</strong>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        $_SESSION[$key] = '';
    }
}
$authors = mysqli_query($con, "SELECT a.*, (SELECT COUNT(*) FROM `blogs` b WHERE b.author_id = a.id) AS blog_count
                               FROM `blog_authors` a ORDER BY a.id ASC");
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Blog Authors</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <a href="<?php echo ADMIN_URL; ?>blog-authors/form.php" class="btn bg-success text-white float-end">Add New Author</a>
            </div>
            <div class="card-body">
                <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>SR No.</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Blogs</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; while ($row = mysqli_fetch_assoc($authors)) { ?>
                            <tr>
                                <th scope="row"><?php echo $i++; ?></th>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['designation'] ?? ''); ?></td>
                                <td><?php echo (int) $row['blog_count']; ?></td>
                                <td><?php echo $row['status'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>'; ?></td>
                                <td>
                                    <a href="<?php echo ADMIN_URL; ?>blog-authors/form.php?id=<?php echo (int) $row['id']; ?>" class="btn btn-sm btn-primary"><i class="ri-pencil-fill align-bottom"></i> Edit</a>
                                    <?php if ((int) $row['blog_count'] === 0) { ?>
                                        <a href="<?php echo ADMIN_URL; ?>blog-authors/index.php?delete=<?php echo (int) $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this author?');"><i class="ri-delete-bin-fill align-bottom"></i> Delete</a>
                                    <?php } ?>
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
