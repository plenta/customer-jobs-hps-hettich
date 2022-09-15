import $ from 'jquery';

document.addEventListener('DOMContentLoaded', function () {
    const $select = $('#container select');
    if ($select.length) {
        import('selectric/src/jquery.selectric').then((selectric) => {
            $select.selectric({
                disableOnMobile: false,
            });

            $('.selectric-wrapper').parentsUntil('.inside').each(function () {
                if ($(this).hasClass('block')) {
                    $(this).addClass('force-overflow-visible');
                }
            });
        });
    }
});