export default class ModalHandler {
    constructor() {
        this.modals = new Map();
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        window.addEventListener('show-modal', (event) => {
            this.show(event.detail.id, event.detail.options);
        });

        window.addEventListener('hide-modal', (event) => {
            this.hide(event.detail.id);
        });

        window.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                this.hideAll();
            }
        });
    }

    show(id, options = {}) {
        const existingModal = this.modals.get(id);
        if (existingModal) {
            existingModal.show();
            return;
        }

        const modal = this.createModal(id, options);
        document.body.appendChild(modal);
        this.modals.set(id, modal);

        // Trigger enter animation
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('.modal-content').classList.remove('translate-y-4', 'opacity-0');
        }, 100);
    }

    hide(id) {
        const modal = this.modals.get(id);
        if (!modal) return;

        // Trigger exit animation
        modal.classList.add('opacity-0');
        modal.querySelector('.modal-content').classList.add('translate-y-4', 'opacity-0');

        setTimeout(() => {
            modal.remove();
            this.modals.delete(id);
        }, 300);
    }

    hideAll() {
        this.modals.forEach((modal, id) => {
            this.hide(id);
        });
    }

    createModal(id, { title = '', content = '', size = 'md', closable = true } = {}) {
        const modal = document.createElement('div');
        modal.id = id;
        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-modal', 'true');
        modal.className = `
            fixed inset-0 z-50 overflow-y-auto
            flex items-center justify-center min-h-screen
            px-4 py-6 sm:px-0
            transition-opacity duration-300 ease-out
            opacity-0
        `;

        const sizeClasses = {
            sm: 'sm:max-w-sm',
            md: 'sm:max-w-md',
            lg: 'sm:max-w-lg',
            xl: 'sm:max-w-xl',
            '2xl': 'sm:max-w-2xl',
            '3xl': 'sm:max-w-3xl',
            '4xl': 'sm:max-w-4xl',
            '5xl': 'sm:max-w-5xl',
            full: 'sm:max-w-full'
        };

        modal.innerHTML = `
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div class="modal-content relative bg-white rounded-lg max-w-full w-full ${sizeClasses[size]} mx-auto
                transform transition-all duration-300 ease-out
                translate-y-4 opacity-0"
            >
                ${title ? `
                    <div class="flex items-center justify-between p-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            ${title}
                        </h3>
                        ${closable ? `
                            <button type="button" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        ` : ''}
                    </div>
                ` : ''}

                <div class="p-4">
                    ${content}
                </div>
            </div>
        `;

        if (closable) {
            // Close on backdrop click
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    this.hide(id);
                }
            });

            // Close on button click
            const closeButton = modal.querySelector('button');
            if (closeButton) {
                closeButton.addEventListener('click', () => {
                    this.hide(id);
                });
            }
        }

        return modal;
    }
} 