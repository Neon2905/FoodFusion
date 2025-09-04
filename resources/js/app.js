import Alpine from 'alpinejs';
import './bootstrap';

window.Alpine = Alpine;

Alpine.store('modals', {
    login: false,
    register: false
});

window.toggleLoginModal = function () {
    Alpine.store('modals').login = !Alpine.store('modals').login;
};

window.toggleRegisterModal = function () {
    Alpine.store('modals').register = !Alpine.store('modals').register;
};


Alpine.start();
