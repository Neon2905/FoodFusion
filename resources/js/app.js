import Alpine from 'alpinejs';
import './bootstrap';

window.Alpine = Alpine;

// Persistent Alpine store for modals
(() => {
    const MODALS_KEY = 'ff_modals_v1';

    const loadModals = () => {
        try {
            const raw = localStorage.getItem(MODALS_KEY);
            if (!raw) return { login: false, register: false };
            const parsed = JSON.parse(raw);
            return {
                login: !!parsed.login,
                register: !!parsed.register
            };
        } catch (e) {
            return { login: false, register: false };
        }
    };

    const saveModals = (state) => {
        try {
            localStorage.setItem(MODALS_KEY, JSON.stringify({
                login: !!state.login,
                register: !!state.register
            }));
        } catch (e) {
            // noop
        }
    };

    // Initialize store from localStorage
    Alpine.store('modals', loadModals());

    // Ensure localStorage is populated the first time
    saveModals(Alpine.store('modals'));

    // Exposed toggles that also persist state
    window.toggleLoginModal = function () {
        const s = Alpine.store('modals');
        s.login = !s.login;
        s.register = false;
        saveModals(s);
    };

    window.toggleRegisterModal = function () {
        const s = Alpine.store('modals');
        s.register = !s.register;
        s.login = false;
        saveModals(s);
    };

    // Sync across tabs/windows
    window.addEventListener('storage', (e) => {
        if (e.key !== MODALS_KEY) return;
        try {
            const data = JSON.parse(e.newValue);
            if (!data) return;
            Object.assign(Alpine.store('modals'), {
                login: !!data.login,
                register: !!data.register
            });
        } catch (err) {
            // ignore parse errors
        }
    });
})();

Alpine.start();

window.redirect = function (to) {
    window.location.href = to;
}
