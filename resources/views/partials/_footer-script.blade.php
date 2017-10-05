<script>
    function ulToHide(ul) {
        ul.hide();
    }

    function whenClickMenu(a, ul) {
        a.on('click', function(e) {
            e.preventDefault();

            ul.slideToggle('fast');
        });
    }

    (function() {
    'use strict';
        var ul = $('ul#sub-report, ul#sub-article');

        // hide ul
        ulToHide(ul);

        // click sidebar Report menu
        whenClickMenu($('a#report'), $('ul#sub-report'));
        // whenClickMenu($('a#article'), $('ul#sub-article'));
    })();
</script>