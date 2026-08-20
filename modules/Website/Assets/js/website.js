document.addEventListener('alpine:init', () => {
    Alpine.data('floorPlanDemo', () => ({
        floorPlan: null,
        selectedChair: null,
        loading: false,

        init() {
            this.fetchFloorPlan();
        },

        async fetchFloorPlan() {
            this.loading = true;
            try {
                const res = await fetch('/api/v1/website/floor-plan-demo');
                this.floorPlan = await res.json();
            } catch {
                this.floorPlan = null;
            } finally {
                this.loading = false;
            }
        },

        statusColor(status) {
            const colors = { available: '#22c55e', occupied: '#ef4444', maintenance: '#eab308' };
            return colors[status] || '#94a3b8';
        },

        statusBorder(status) {
            const colors = { available: '#16a34a', occupied: '#dc2626', maintenance: '#ca8a04' };
            return colors[status] || '#64748b';
        },
    }));

    Alpine.store('website', {
        universe: 'men',
        locale: document.documentElement.lang || 'ar',

        toggleUniverse(u) {
            this.universe = u;
            document.documentElement.dataset.theme = u;
        },
    });
});

Alpine.start();
