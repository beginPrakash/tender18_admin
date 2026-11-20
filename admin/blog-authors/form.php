<?php
include "../includes/authentication.php";
$pages = 'blog_authors';
include '../includes/header.php';
include '_guard.php';

$id = (int) ($_GET['id'] ?? 0);
$author = ['name' => '', 'designation' => '', 'bio' => '', 'status' => 1];
if ($id) {
    $found = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `blog_authors` WHERE id = $id"));
    if (!$found) {
        echo "<script>window.location.href='" . ADMIN_URL . "blog-authors/index.php';</script>";
        exit;
    }
    $author = $found;
}

$error = '';
if (isset($_POST['submit'])) {
    $author['name'] = trim($_POST['name'] ?? '');
    $author['designation'] = trim($_POST['designation'] ?? '');
    $author['bio'] = trim($_POST['bio'] ?? '');
    $author['status'] = !empty($_POST['status']) ? 1 : 0;

    if ($author['name'] === '') {
        $error = 'Name is required.';
    } else {
        if ($id) {
            $stmt = mysqli_prepare($con, "UPDATE `blog_authors` SET name = ?, designation = ?, bio = ?, status = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, 'sssii', $author['name'], $author['designation'], $author['bio'], $author['status'], $id);
        } else {
            $stmt = mysqli_prepare($con, "INSERT INTO `blog_authors` (name, designation, bio, status) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'sssi', $author['name'], $author['designation'], $author['bio'], $author['status']);
        }
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = $id ? 'Author updated.' : 'Author added.';
            echo "<script>window.location.href='" . ADMIN_URL . "blog-authors/index.php';</script>";
            exit;
        }
        $error = 'Something went wrong.';
    }
}
if ($error) {
    echo '<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert"><strong>' . htmlspecialchars($error) . '</strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
}
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0"><?php echo $id ? 'Edit Author' : 'Add Author'; ?></h4>
            <a href="<?php echo ADMIN_URL; ?>blog-authors/index.php" class="btn btn-light btn-sm">&larr; Back to Authors</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="" method="post" id="author_form">
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name : <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" maxlength="150" placeholder="e.g. Tender18 Team" value="<?php echo htmlspecialchars($author['name']); ?>">
                        </div>
                        <div class="w-100 m-0"></div>
                        <div class="col-md-6">
                            <label for="designation" class="form-label">Designation :</label>
                            <input type="text" name="designation" id="designation" class="form-control" maxlength="150" placeholder="e.g. Tender &amp; GeM Experts" value="<?php echo htmlspecialchars($author['designation'] ?? ''); ?>">
                            <small class="text-muted">Shown under the name on the "Written by" card.</small>
                        </div>
                        <div class="w-100 m-0"></div>
                        <div class="col-md-6">
                            <label for="bio" class="form-label">Short Bio :</label>
                            <textarea name="bio" id="bio" rows="4" class="form-control" maxlength="500" placeholder="One or two sentences about the author"><?php echo htmlspecialchars($author['bio'] ?? ''); ?></textarea>
                        </div>
                        <div class="w-100 m-0"></div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="status" id="status" value="1" <?php echo $author['status'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="status">Active (can be chosen on blogs)</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" name="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
<script>
    $(function () { $('#author_form').validate({ rules: { name: 'required' } }); });
</script>
