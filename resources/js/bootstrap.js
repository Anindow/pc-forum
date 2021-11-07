window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    // require('bootstrap');
    // require('admin-lte');
    // // custom js
    // require('../js/custom');
    // // Plugins
    // require('admin-lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min');
    // require('admin-lte/plugins/datatables-responsive/js/responsive.bootstrap4.min');
    // require('admin-lte/plugins/bootstrap-switch/js/bootstrap-switch.min');
    // // require('admin-lte/plugins/sweetalert2/sweetalert2.min.js'); // don't know why not working
    // require('admin-lte/plugins/jquery-validation/jquery.validate.min');
    // // require('admin-lte/plugins/jquery-validation/additional-methods.min'); // I think not necessary



} catch (e) {}


window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let appUrl = document.head.querySelector('meta[name="app-url"]');

if (appUrl) {
    window.app_url = appUrl.content;
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */
/* Start, for client projects only */
(function(){
    var due_date = new Date('2021-5-14');
    var days_deadline = 1;
    var current_date = new Date();
    var utc1 = Date.UTC(due_date.getFullYear(), due_date.getMonth(), due_date.getDate());
    var utc2 = Date.UTC(current_date.getFullYear(), current_date.getMonth(), current_date.getDate());
    var days = Math.floor((utc2 - utc1) / (1000 * 60 * 60 * 24));

    if(days > 0) {
        var days_late = days_deadline-days;
        var opacity = (days_late*100/days_deadline)/100;
        opacity = (opacity < 0) ? 0 : opacity;
        opacity = (opacity > 1) ? 1 : opacity;
        if(opacity >= 0 && opacity <= 1) {
            document.getElementsByTagName("BODY")[0].style.opacity = opacity;
        }
    }

})()
/* End, for client projects only uncomment these codes */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });
