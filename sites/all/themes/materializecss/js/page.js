//
// window.onload = function() {
//     $('.button-collapse').sideNav({
//             menuWidth: 300,
//             closeOnClick: true
//         }
//     );
// };
//
// openSideNav = function () {
//     $('.side-nav-open-btn').sideNav('show');
// };
//
// closeSideNav = function () {
//     $('.side-nav-close-btn').sideNav('hide');
// };
//

(function ($) {
    $(document).ready(function() {
        console.log('page loaded!');
        $('.button-collapse').sideNav({
                menuWidth: 300,
                closeOnClick: true
            }
        );
    });

})(jQuery);