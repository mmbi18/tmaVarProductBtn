jQuery(document).ready(function($) {
    var variations = <?php echo json_encode($variations); ?>;
    var defaultImage = $('.wp-post-image img').attr('src'); // ذخیره تصویر پیش‌فرض محصول

    $('.variations_form').each(function() {
        $(this).find('.variations select').each(function() {
            var select = $(this);
            var options = '';
            variations.forEach(function(variation) {
                var value = variation.attributes[select.attr('name')];
                var image = variation.image.thumb_src;
                if (image) {
                    options += '<button type="button" class="vpbs-option" data-value="'+value+'" style="background-image: url('+image+');"></button>';
                } else {
                    options += '<button type="button" class="vpbs-option" data-value="'+value+'">'+value+'</button>';
                }
            });
            if (!select.next('.vpbs-options').length) {
                select.hide().after('<div class="vpbs-options">'+options+'</div>');
            }
        });
    });

    $('.vpbs-option').on('click', function() {
        var button = $(this);
        var select = button.closest('.variations').find('select');
        var value = button.data('value');
        select.val(value).trigger('change');
        button.siblings().removeClass('selected');
        button.addClass('selected');

        // تغییر تصویر به تصویر پیش‌فرض پس از انتخاب
       // $('.woocommerce-main-image img').attr('src', defaultImage);
    });
});
