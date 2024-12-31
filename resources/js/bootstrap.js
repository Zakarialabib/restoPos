import axios from 'axios';
import { router } from '@inertiajs/react';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Configure Inertia router
router.on('start', () => {
  // Optional: Add loading state
  document.body.classList.add('loading');
});

router.on('finish', () => {
  // Optional: Remove loading state
  document.body.classList.remove('loading');
});
