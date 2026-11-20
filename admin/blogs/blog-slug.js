// Slug field behaviour for blogs/add.php and blogs/edit.php.
// #slug[data-auto="1"] follows the title until the user edits the slug by hand.
//
// Uniqueness is checked in the background (check_slug.php) while typing, and the
// result is fed to a synchronous validation rule. We deliberately do NOT use
// jQuery Validate's "remote" rule: when a remote check is still pending at submit
// time, the plugin swallows the first click on Submit and the form isn't saved.
// The server checks uniqueness again on save.
(function ($) {
    var blogId = 0;
    var taken = {};   // slug -> error message, for slugs the server reported as used
    var checked = {}; // slug -> true once the server answered

    function makeSlug(text) {
        return String(text || '')
            .toLowerCase()
            .replace(/&/g, ' and ')
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '')
            .substring(0, 200);
    }

    $.validator.addMethod('slugFree', function (value) {
        return !taken[value];
    }, function (params, element) {
        return taken[$(element).val()] || 'This slug is already used by another blog.';
    });

    function checkSlug(slug) {
        if (!slug || checked[slug]) {
            $('#slug').valid();
            return;
        }
        $.getJSON('check_slug.php', { slug: slug, id: blogId }).done(function (res) {
            checked[slug] = true;
            if (res === true) {
                delete taken[slug];
            } else {
                taken[slug] = res;
            }
            if ($('#slug').val() === slug) $('#slug').valid();
        });
    }

    $(function () {
        var $title = $('#title');
        var $slug = $('#slug');
        var $preview = $('#slug_preview');
        var follow = $slug.data('auto') == 1 && $slug.val() === '';
        var timer;

        function refresh() {
            $preview.text($slug.val() || '…');
            clearTimeout(timer);
            timer = setTimeout(function () { checkSlug($slug.val()); }, 400);
        }

        $title.on('input', function () {
            if (!follow) return;
            $slug.val(makeSlug($title.val()));
            refresh();
        });

        $slug.on('input', function () {
            follow = false;
            // keep typing natural: lowercase, spaces/underscores -> dash, drop other characters
            var v = $slug.val().toLowerCase().replace(/[\s_]+/g, '-').replace(/[^a-z0-9-]/g, '').replace(/-{2,}/g, '-');
            if (v !== $slug.val()) $slug.val(v);
            refresh();
        });

        $slug.on('blur', function () {
            $slug.val(makeSlug($slug.val()));
            if ($slug.val() === '') follow = $slug.data('auto') == 1;
            refresh();
        });

        $preview.text($slug.val() || '…');
    });

    // jQuery Validate rule shared by both forms
    window.blogSlugRule = function (id) {
        blogId = id || 0;
        return { required: true, slugFree: true };
    };
})(jQuery);
