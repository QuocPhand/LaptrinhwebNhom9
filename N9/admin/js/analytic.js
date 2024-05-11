$(document).ready(function(){
    function fetchData(range) {
        fetch('sale_report.php?range=' + range)
        .then(response => response.json())
        .then(data => {
            updateAnalyticTable(data);
            updateTotal(data);
        })
        .catch(error => console.error('Error fetching products:', error));
    }

    function updateAnalyticTable(data) {
        let table = '';
    
        data.forEach(order => {
            table += `<tr>
                        <td>${order.order_id}</td>
                        <td>${order.name}</td>
                        <td>${order.order_date}</td>
                        <td>${order.total}</td>
                    </tr>`;
        });
        $('#analytic-table tbody').html(table);
    }

    function updateTotal(data) {
        let total = 0;

        data.forEach(order => {
            total += parseFloat(order.total);
        });

        // Add the total row to the table
        $('#analytic-table tbody').append(`<tr class="total-row">
                                            <td colspan="3">Tổng</td>
                                            <td>${total}</td>
                                        </tr>`);
    }

    $('#date-range').change(function() {
        var selectedRange = $(this).val();
        fetchData(selectedRange);
    });

    
    var defaultRange = $('#date-range').val();
    fetchData(defaultRange);
});