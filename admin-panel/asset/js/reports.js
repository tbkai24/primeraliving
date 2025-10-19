document.addEventListener('DOMContentLoaded', function() {
    const revenueChartCtx = document.getElementById('revenueChart').getContext('2d');
    const currentRevenueEl = document.getElementById('currentRevenue');
    const latePaymentsEl = document.getElementById('latePayments');
    const expiringLeasesEl = document.getElementById('expiringLeases');
    const tableBody = document.querySelector('#reportTable tbody');
    let revenueChart;

    function fetchReports() {
        fetch('../handlers/reports_handler.php', {
            method: 'POST',
            body: new URLSearchParams({ action: 'get_reports' })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                // Update summary cards
                currentRevenueEl.textContent = `₱${data.summary.currentRevenue.toLocaleString()}`;
                latePaymentsEl.textContent = data.summary.latePayments;
                expiringLeasesEl.textContent = data.summary.expiringLeases;

                // Render chart
                const months = data.chart.labels;
                const revenueData = data.chart.data;

                if(revenueChart) revenueChart.destroy();

                revenueChart = new Chart(revenueChartCtx, {
                    type: 'bar',
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Revenue (₱)',
                            data: revenueData,
                            backgroundColor: 'rgba(42, 111, 158, 0.6)',
                            borderColor: '#2A6F9E',
                            borderWidth: 1,
                            borderRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { labels: { color: '#2A6F9E' } } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { color: '#2A6F9E', callback: v => '₱' + v.toLocaleString() },
                                grid: { color: 'rgba(42, 111, 158, 0.1)' }
                            },
                            x: { ticks: { color: '#2A6F9E' }, grid: { display: false } }
                        }
                    }
                });

                // Populate table
                tableBody.innerHTML = '';
                if(data.transactions.length === 0){
                    tableBody.innerHTML = `<tr><td colspan="8" class="text-center">No transactions found</td></tr>`;
                } else {
                    data.transactions.forEach(tx => {
                        tableBody.innerHTML += `
                            <tr>
                                <td>${tx.id}</td>
                                <td>${tx.tenant_email}</td>
                                <td>${tx.invoice_id}</td>
                                <td>${tx.description}</td>
                                <td>₱${tx.amount.toLocaleString()}</td>
                                <td>${tx.status}</td>
                                <td>${tx.method}</td>
                                <td>${tx.created_at}</td>
                            </tr>
                        `;
                    });
                }
            }
        });
    }

    // Export PDF
    document.getElementById('exportPDF').addEventListener('click', () => {
        const pdf = new jsPDF('p', 'mm', 'a4');
        let yOffset = 10;

        pdf.setFontSize(18);
        pdf.setTextColor(42, 111, 158);
        pdf.text("Monthly Revenue Report", 105, yOffset, { align: 'center' });
        yOffset += 10;

        // Summary
        pdf.setFontSize(12);
        pdf.text(`Current Month Revenue: ${currentRevenueEl.textContent}`, 15, yOffset); yOffset+=8;
        pdf.text(`Late Payments: ${latePaymentsEl.textContent}`, 15, yOffset); yOffset+=8;
        pdf.text(`Expiring Leases: ${expiringLeasesEl.textContent}`, 15, yOffset); yOffset+=5;

        // Chart
        const chartImg = document.getElementById('revenueChart').toDataURL('image/png');
        const pageWidth = pdf.internal.pageSize.getWidth();
        const chartWidth = pageWidth - 30;
        const chartHeight = (document.getElementById('revenueChart').height / document.getElementById('revenueChart').width) * chartWidth;
        pdf.addImage(chartImg, 'PNG', 15, yOffset, chartWidth, chartHeight);
        yOffset += chartHeight + 10;

        // Table
        pdf.autoTable({ html: '#reportTable', startY: yOffset, theme: 'grid', headStyles: { fillColor: [42,111,158] }, styles:{ fontSize:10 } });
        pdf.save('monthly_report.pdf');
    });

    fetchReports();
});
