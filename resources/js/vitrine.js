// Vitrine d'un producteur : un clic sur un produit l'ouvre en grand. La fenêtre grandit depuis
// la ligne du produit (technique « FLIP » : on part de la position de la ligne, on anime vers la fenêtre).
document.addEventListener('alpine:init', () => {
    window.Alpine.data('vitrine', () => ({
        current: null,
        origin: null,

        reducedMotion() {
            return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        },

        // Transformation qui place la fenêtre exactement sur la ligne du produit.
        fromRow(panel) {
            const from = this.origin.getBoundingClientRect();
            const to = panel.getBoundingClientRect();

            return `translate(${from.left - to.left}px, ${from.top - to.top}px) scale(${from.width / to.width}, ${from.height / to.height})`;
        },

        async open(id, row) {
            this.current = id;
            this.origin = row;
            this.$refs.dialog.showModal();
            await this.$nextTick();
            if (this.reducedMotion()) {
                return;
            }

            const panel = this.$refs.panel;
            const easing = 'cubic-bezier(.2, .9, .25, 1)';
            panel.animate([{ transform: this.fromRow(panel), borderRadius: '1rem' }, { transform: 'none' }], { duration: 380, easing });
            panel.querySelectorAll(`[data-product="${id}"] [data-fade]`).forEach((el) =>
                el.animate([{ opacity: 0, transform: 'translateY(.5rem)' }, { opacity: 1, transform: 'none' }], { duration: 260, delay: 200, easing, fill: 'backwards' }),
            );
            const photo = panel.querySelector(`[data-product="${id}"] [data-photo]`);
            photo?.animate([{ transform: 'scale(.35)', borderRadius: '9999px', opacity: 0.4 }, { transform: 'none', opacity: 1 }], { duration: 420, easing });
            this.$refs.dialog.animate([{ backgroundColor: 'transparent' }, {}], { duration: 380 });
        },

        async close() {
            const dialog = this.$refs.dialog;
            if (!dialog.open) {
                return;
            }
            if (!this.reducedMotion() && this.origin) {
                const panel = this.$refs.panel;
                await panel.animate([{ transform: 'none', opacity: 1 }, { transform: this.fromRow(panel), opacity: 0 }], { duration: 240, easing: 'ease-in' }).finished;
            }
            dialog.close();
            this.origin?.focus();
            this.current = null;
        },
    }));
});
