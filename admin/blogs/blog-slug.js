// Slug field behaviour for blogs/add.php and blogs/edit.php.
// #slug[data-auto="1"] follows the title until the user edits the slug by hand.
(function ($) {
    function makeSlug(text) {
        return String(text || '')
            .toLowerCase()
            .replace(/&/g, ' and ')
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '')
            .substring(0, 200);
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
            timer = setTimeout(function () {
                if ($slug.val() !== '') $slug.valid();
            }, 400);
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
    window.blogSlugRule = function (blogId) {
        return {
            required: true,
            remote: {
                url: 'check_slug.php',
                type: 'get',
                data: {
                    slug: function () { return $('#slug').val(); },
                    id: blogId || 0
                }
            }
        };
    };
})(jQuery);
