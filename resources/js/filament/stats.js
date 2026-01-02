document.addEventListener('alpine:init', () => {
    Alpine.effect(() => {
        setTimeout(() => {
            document.querySelectorAll(
                '.filament-stats-overview-widget canvas'
            ).forEach(canvas => {
                const chart = Chart.getChart(canvas);
                if (!chart) return;

                chart.options.animation = {
                    duration: 800,
                    easing: 'easeInOutQuart',
                };

                chart.update();
            });
        }, 300);
    });
});