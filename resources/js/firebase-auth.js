import { initializeApp } from 'firebase/app';
import { getAuth, signInWithPopup, GoogleAuthProvider } from 'firebase/auth';

const firebaseConfig = {
    apiKey: import.meta.env.VITE_FIREBASE_API_KEY,
    authDomain: import.meta.env.VITE_FIREBASE_AUTH_DOMAIN,
    projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID,
};

let auth, provider;

function boot() {
    if (!auth) {
        const app = initializeApp(firebaseConfig);
        auth = getAuth(app);
        provider = new GoogleAuthProvider();
    }
}

async function signInWithGoogle() {
    boot();
    const result = await signInWithPopup(auth, provider);
    const token = await result.user.getIdToken();

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/auth/google/callback';

    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = document.querySelector('meta[name="csrf-token"]').content;

    const tokenInput = document.createElement('input');
    tokenInput.type = 'hidden';
    tokenInput.name = 'token';
    tokenInput.value = token;

    form.append(csrf, tokenInput);
    document.body.appendChild(form);
    form.submit();
}

window.googleSignIn = async function (btn) {
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>';

    try {
        await signInWithGoogle();
    } catch (err) {
        btn.disabled = false;
        btn.innerHTML = originalHTML;

        // Show error below the button
        let errEl = document.getElementById('google-sign-in-error');
        if (!errEl) {
            errEl = document.createElement('p');
            errEl.id = 'google-sign-in-error';
            errEl.className = 'mt-2 text-xs text-red-600 dark:text-red-400 text-center';
            btn.after(errEl);
        }
        errEl.textContent = err.code === 'auth/popup-closed-by-user'
            ? 'Sign-in cancelled.'
            : 'Google sign-in failed. Please try again.';
    }
};
