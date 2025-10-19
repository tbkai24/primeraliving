// dashboard.js
document.addEventListener("DOMContentLoaded", function() {

    // ================= Payments Chart =================
    const paymentsChartEl = document.getElementById('paymentsChart');
    if(paymentsChartEl){
        const ctx = paymentsChartEl.getContext('2d');

        // Example: replace these with dynamic PHP values or data-* attributes
        const paymentLabels = paymentsChartEl.dataset.labels ? JSON.parse(paymentsChartEl.dataset.labels) : [];
        const paymentAmounts = paymentsChartEl.dataset.amounts ? JSON.parse(paymentsChartEl.dataset.amounts) : [];

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: paymentLabels,
                datasets: [{
                    label: 'Amount Paid',
                    data: paymentAmounts,
                    backgroundColor: 'rgba(42,111,158,0.7)',
                    borderColor: 'rgba(42,111,158,1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'Amount ($)' } },
                    x: { title: { display: true, text: 'Month' } }
                }
            }
        });
    }

    // ================= Lease Timeline Chart =================
    const leaseChartEl = document.getElementById('leaseChart');
    if(leaseChartEl){
        const leaseCtx = leaseChartEl.getContext('2d');

        // Example: replace these with dynamic PHP values or data-* attributes
        const leaseProgress = leaseChartEl.dataset.progress ? parseInt(leaseChartEl.dataset.progress) : 0;

        new Chart(leaseCtx, {
            type: 'bar',
            data: {
                labels: ['Lease Duration'],
                datasets: [
                    {
                        label: 'Completed',
                        data: [leaseProgress],
                        backgroundColor: 'rgba(42,111,158,0.7)'
                    },
                    {
                        label: 'Remaining',
                        data: [100 - leaseProgress],
                        backgroundColor: 'rgba(200,200,200,0.3)'
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: { legend: { position: 'bottom' } },
                scales: { x: { max: 100, beginAtZero: true, display: false }, y: { display: false } }
            }
        });
    }

});
