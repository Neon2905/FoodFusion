import Alpine from 'alpinejs';
import './bootstrap';

window.Alpine = Alpine;

Alpine.store('modals', {
    login: false,
    register: false
});

// TODO: Refactor these functions into Alpine component methods
// TODO: fix this to store the state after page reload

window.toggleLoginModal = function () {
    Alpine.store('modals').login = !Alpine.store('modals').login;
    Alpine.store('modals').register = false;
};

window.toggleRegisterModal = function () {
    Alpine.store('modals').register = !Alpine.store('modals').register;
    Alpine.store('modals').login = false;
};

Alpine.start();

window.redirect = function (to) {
    window.location.href = to;
}
