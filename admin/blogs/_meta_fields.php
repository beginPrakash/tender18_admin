<?php
// Author, Publish Date and Tags fields for blogs/add.php and blogs/edit.php.
// Expects: $meta_author_id (int), $meta_publish_date ('Y-m-d'), $meta_tags (array of names)
$author_options = get_blog_author_options($con, $meta_author_id);
?>
<div class="col-xxl-12 col-md-12">
    <hr class="my-1">
</div>
<div class="col-xxl-12 col-md-12">
    <div class="row g-3" style="max-width: 50%; min-width: 320px;">
        <div class="col-sm-7">
            <label for="author_id" class="form-label">Author :</label>
            <select name="author_id" id="author_id" class="form-select">
                <option value="">Default (<?php echo htmlspecialchars($author_options[get_default_blog_author_id($con)] ?? 'Tender18 Team'); ?>)</option>
                <?php foreach ($author_options as $aid => $aname) { ?>
                    <option value="<?php echo $aid; ?>" <?php echo $aid === (int) $meta_author_id ? 'selected' : ''; ?>><?php echo htmlspecialchars($aname); ?></option>
                <?php } ?>
            </select>
            <small class="text-muted"><a href="<?php echo ADMIN_URL; ?>blog-authors/index.php" target="_blank">Manage authors</a></small>
        </div>
        <div class="col-sm-5">
            <label for="publish_date" class="form-label">Publish Date :</label>
            <input type="date" name="publish_date" id="publish_date" class="form-control" value="<?php echo htmlspecialchars($meta_publish_date); ?>">
            <small class="text-muted">Empty = date the blog was created</small>
        </div>
    </div>
</div>
<div class="col-xxl-12 col-md-12">
    <div class="col-md-6">
        <label for="tag_input" class="form-label">Tags :</label>
        <div class="blog-tag-box form-control" id="tag_box">
            <input type="text" id="tag_input" list="tag_suggestions" placeholder="Type a tag and press Enter or comma" autocomplete="off">
        </div>
        <input type="hidden" name="tags" id="tags" value="<?php echo htmlspecialchars(implode(',', $meta_tags)); ?>">
        <datalist id="tag_suggestions">
            <?php foreach (get_all_blog_tag_names($con) as $tname) { ?>
                <option value="<?php echo htmlspecialchars($tname); ?>"></option>
            <?php } ?>
        </datalist>
        <small class="text-muted">Shown as tag buttons at the end of the blog. Existing tags are suggested as you type.</small>
    </div>
</div>
<style>
    .blog-tag-box { display: flex; flex-wrap: wrap; gap: 6px; align-items: center; min-height: 42px; height: auto; cursor: text; }
    .blog-tag-box input { border: 0; outline: 0; flex: 1 1 180px; min-width: 140px; padding: 2px 0; background: transparent; }
    .blog-tag-pill { display: inline-flex; align-items: center; gap: 6px; background: #016492; color: #fff; border-radius: 999px; padding: 3px 6px 3px 12px; font-size: 13px; line-height: 1.4; }
    .blog-tag-pill button { border: 0; background: rgba(255,255,255,.25); color: #fff; width: 18px; height: 18px; border-radius: 50%; line-height: 16px; padding: 0; font-size: 13px; }
    .blog-tag-pill button:hover { background: rgba(255,255,255,.45); }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var box = document.getElementById('tag_box');
        var input = document.getElementById('tag_input');
        var hidden = document.getElementById('tags');
        var tags = hidden.value ? hidden.value.split(',').filter(Boolean) : [];

        function key(t) { return t.toLowerCase().replace(/[^a-z0-9]+/g, ''); }
        function render() {
            box.querySelectorAll('.blog-tag-pill').forEach(function (p) { p.remove(); });
            tags.forEach(function (t, i) {
                var pill = document.createElement('span');
                pill.className = 'blog-tag-pill';
                pill.textContent = t;
                var x = document.createElement('button');
                x.type = 'button';
                x.innerHTML = '&times;';
                x.title = 'Remove';
                x.onclick = function () { tags.splice(i, 1); render(); };
                pill.appendChild(x);
                box.insertBefore(pill, input);
            });
            hidden.value = tags.join(',');
        }
        function add(raw) {
            raw.split(',').forEach(function (t) {
                t = t.replace(/\s+/g, ' ').trim().substring(0, 100);
                if (t && key(t) && !tags.some(function (x) { return key(x) === key(t); })) tags.push(t);
            });
            input.value = '';
            render();
        }
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ',') { e.preventDefault(); add(input.value); }
            else if (e.key === 'Backspace' && input.value === '' && tags.length) { tags.pop(); render(); }
        });
        // picking a suggestion from the datalist
        input.addEventListener('change', function () { if (input.value.trim()) add(input.value); });
        input.addEventListener('blur', function () { if (input.value.trim()) add(input.value); });
        box.addEventListener('click', function (e) { if (e.target === box) input.focus(); });
        render();
    });
</script>
