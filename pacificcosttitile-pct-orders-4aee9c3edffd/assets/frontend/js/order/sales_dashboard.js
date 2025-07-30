$(document).ready(function () {
    var order_list = '';
    if ($('#orders_listing').length) {
        var flag_val = localStorage.getItem("sales_rep_manager_flag");
        order_list = $('#orders_listing').DataTable({
            // "pageLength": 2,
            "paging": true,
            "lengthChange": false,
            "language": {
                searchPlaceholder: "Search File# or Address",
                paginate: {
                    next: '<span class="fa fa-angle-right"></span>',
                    previous: '<span class="fa fa-angle-left"></span>',
                },
                "emptyTable": "Record(s) not found.",
                "search": "",
            },
            /*"searching": false,*/
            initComplete: function () {


            },
            "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                let lastElement = aData.slice(-1)[0];
                if (lastElement.includes("color~")) {
                    let splitEle = lastElement.split('|');
                    let colorString = splitEle[0].split('color~');
                    $(nRow).css("background-color", colorString[1]);
                    let textColor = splitEle[1].split('text_color~');
                    $(nRow).css("color", textColor[1]);
                }
            },
            // dom: 'Bfrtip',
            "dom": 'lf<"orders_listing_filter">rtip',
            buttons: [],
            "drawCallback": function () {

            },
            "fnInitComplete": function (oSettings, json) {
                $(".fa-info-circle").mouseenter(function () {
                    $(this).closest('td').find('span.tooltiptext').css("visibility", "visible").css("border-radius", "3px");
                }).mouseleave(function () {
                    $(this).closest('td').find('span.tooltiptext').css("visibility", "hidden").css("border-radius", "0px");
                });
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "get-sales-orders", // json datasource
                type: "post", // method  , by default get
                data: function (d) {
                    // d.status = $('#orders_filter').val();
                    d.order_type = $('#order_type_filter').val();
                    //d.month = $('#month_filter').val();
                    d.sales_user = $('#sales_user_filter').val();
                    d.sales_rep_manager_flag = flag_val;
                },
                dataFilter: function (data) {
                    localStorage.removeItem("sales_rep_manager_flag");
                    var json = jQuery.parseJSON(data);
                    var countingData = json.count_data;
                    // if (countingData) {
                    // 	console.log(countingData.refi_open_count);
                    // 	$('#refi_open_count').html(countingData.refi_open_count);
                    // 	$('#sale_open_count').html(countingData.sale_open_count);
                    // 	$('#open_order_count').html(countingData.open_order_count);

                    // 	$('#refi_close_count').html(countingData.refi_close_count);
                    // 	$('#sale_close_count').html(countingData.sale_close_count);
                    // 	$('#close_order_count').html(countingData.close_order_count);

                    // 	$('#refi_total_premium').html(countingData.refi_total_premium);
                    // 	$('#sale_total_premium').html(countingData.sale_total_premium);
                    // 	$('#total_premium').html(countingData.total_premium);

                    // 	$('#refi_close_order_percetage').html(countingData.refi_close_order_percetage);
                    // 	$('#sale_close_order_percetage').html(countingData.sale_close_order_percetage);
                    // 	$('#close_order_percetage').html(countingData.close_order_percetage);

                    // } 
                    json.recordsTotal = json.recordsTotal;
                    json.recordsFiltered = json.recordsFiltered;
                    json.data = json.data;
                    return JSON.stringify(json);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    localStorage.removeItem("sales_rep_manager_flag");
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        alert("You are logged out. Please login.");
                    }
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                    $("#orders_listing tbody").append(
                        '<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#orders_listing_processing").css("display", "none");

                }
            }
        });

        $("div#orders_listing_filter").append('<label><select style="width:auto;margin-left:10px;" name="order_type_filter" id="order_type_filter" class="custom-select custom-select-sm form-control form-control-sm"><option value="open"> Select Order Type </option> <option value="resware_orders"> Resware Orders </option><option value="lp_orders">LP Orders</option></select></label>');


        // $("div#orders_listing_filter").append('<label><select style="width:auto;" name="month_filter" id="month_filter" class="custom-select custom-select-sm form-control form-control-sm"> <option value="01"> January </option><option value="02">February</option><option value="03">March</option><option value="04">April</option><option value="05">May</option><option value="06">June</option><option value="07">July</option><option value="08">August</option><option value="09">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select></label><label><select style="width:auto;" name="orders_filter" id="orders_filter" class="custom-select custom-select-sm form-control form-control-sm"> <option value="open"> Open </option><option value="closed">Closed</option><option value="cancelled">Cancelled</option></select></label>');

        // var d = new Date(),

        // 	m = d.getMonth(),

        // 	y = d.getFullYear();

        // $('#month_filter option:eq('+m+')').prop('selected', true);
    }

    $("#order_type_filter").on("change", function () {
        order_list.ajax.reload();
    });

    $("#order_type_filter").on("change", function () {
        localStorage.setItem("sales_rep_manager_flag", true);
        order_list.ajax.reload();
    });

    // $("#orders_filter").on("change", function(){
    //     order_list.ajax.reload();
    // });

    // $("#month_filter").on("change", function(){
    //     order_list.ajax.reload();
    // });

    $("#sales_user_filter").on("change", function () {
        localStorage.setItem("sales_rep_manager_flag", true);
        var user_id = $(this).val();
        window.location.replace(base_url + 'sales-dashboard/' + user_id);
    });

    $("#sales_user_production_filter").on("change", function () {
        var user_id = $(this).val();
        window.location.replace(base_url + 'sales-production-history/' + user_id);
    });

    $("#sales_user_summary_filter").on("change", function () {
        var user_id = $(this).val();
        window.location.replace(base_url + 'sales-summary/' + user_id);
    });

    $("#sales_user_trend_filter").on("change", function () {
        var user_id = $(this).val();
        window.location.replace(base_url + 'trends/' + user_id);
    });

    $("#sales_user_commission_filter").on("change", function () {
        var user_id = $(this).val();
        window.location.replace(base_url + 'sales-commission/' + user_id);
    });

    if ($('.custom__task_button').length > 0) {
        $('.task_show_all').click(function () {
            $(".custom__task_card .custom__task_collapse").collapse('show');
        });
        $('.task_hide_all').click(function () {
            $(".custom__task_card .custom__task_collapse").collapse('hide');
        });
    }

    $("#sales_user_commission_filter").on("change", function () {
        var user_id = $(this).val();
        window.location.replace(base_url + 'sales-commission/' + user_id);
    });

    $("#title_officer_list").on("change", function () {
        var title_officer_survey_id = $(this).val();
        var title_officer_survey_name = $(this).find("option:selected").text();
        if (title_officer_survey_id) {
            $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
            $('#page-preloader').css('display', 'block');
            $.ajax({
                url: base_url + "get-survey-details",
                type: "post",
                data: {
                    title_officer_survey_id: title_officer_survey_id,
                    title_officer_survey_name: title_officer_survey_name
                },
                dataType: "html",
                success: function (response) {

                    var results = JSON.parse(response);
                    console.log(results);
                    if (results.status == 'success') {

                        $('.survey-cards').html(results.survey.survey_cards);
                        $('.survey-table').html(results.survey.survey_rating_details);
                    }
                    else if (results.status == 'error') {
                        alert(results.msg);
                    }
                    $('#page-preloader').css('display', 'none');
                }
            });
        }
    });
});

if (typeof (salesData) != "undefined" && salesData !== null) {
    var salesDataKeys = Object.keys(salesData);
    const openOrderDataset = [];
    const closedOrderDataset = [];
    const premiumTotalDataset = [];

    for (let i = 0; i < salesDataKeys.length; i++) {

        const openOrdersCountForMonth = [];
        const closedOrdersCountForMonth = [];
        const premiumTotalForMonth = [];

        for (let index = 0; index < salesData[salesDataKeys[i]].length; index++) {
            openOrdersCountForMonth.push(salesData[salesDataKeys[i]][index].total_open_count);
            closedOrdersCountForMonth.push(salesData[salesDataKeys[i]][index].total_close_count);
            premiumTotalForMonth.push(salesData[salesDataKeys[i]][index].total_premium);
        }

        openOrderDataset.push(
            {
                label: salesDataKeys[i],
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: openOrdersCountForMonth,
            }
        );
        closedOrderDataset.push(
            {
                label: salesDataKeys[i],
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: closedOrdersCountForMonth,
            }
        );
        premiumTotalDataset.push(
            {
                label: salesDataKeys[i],
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: premiumTotalForMonth,
            }
        )
    }

    Chart.defaults.global.defaultFontFamily = 'Nunito',
        '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    var openOrdersChart = document.getElementById("openOrdersChart");
    var openOrdersLineChart = new Chart(openOrdersChart, {
        type: 'line',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: openOrderDataset,
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 20
                }
            },
            scales: {
                xAxes: [{
                    time: {
                        unit: 'date'
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                }],
                yAxes: [{
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                    },
                    gridLines: {
                        color: "rgba(000, 000, 000, 0.15)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }],
            },
            legend: {
                display: false
            },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 16,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                    label: function (tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
                    }
                }
            }
        }
    });

    var closedOrderChart = document.getElementById("closedOrderChart");
    var closedOrderLineChart = new Chart(closedOrderChart, {
        type: 'line',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: closedOrderDataset,
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 20
                }
            },
            scales: {
                xAxes: [{
                    time: {
                        unit: 'date'
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                }],
                yAxes: [{
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                    },
                    gridLines: {
                        color: "rgba(000, 000, 000, 0.15)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2],
                    }
                }],
            },
            legend: {
                display: false
            },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                    label: function (tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
                    }
                }
            }
        }
    });

    var premiumTotalChart = document.getElementById("premiumTotalChart");
    var premiumTotalLineChart = new Chart(premiumTotalChart, {
        type: 'line',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: premiumTotalDataset,
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 20
                }
            },
            scales: {
                xAxes: [{
                    time: {
                        unit: 'date'
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                }],
                yAxes: [{
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                        // Include a dollar sign in the ticks
                        callback: function (value, index, values) {
                            return '$' + number_format(value);
                        }
                    },
                    gridLines: {
                        color: "rgba(000, 000, 000, 0.15)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }],
            },
            legend: {
                display: false
            },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                    label: function (tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': $' + number_format(tooltipItem.yLabel);
                    }
                }
            }
        }
    });
}

function number_format(number, decimals, dec_point, thousands_sep) {
    // *     example: number_format(1234.56, 2, ',', ' ');
    // *     return: '1 234,56'
    number = (number + '').replace(',', '').replace(' ', '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}


function getPartners(fileId) {
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    $.ajax({
        url: base_url + "get-partners",
        type: "post",
        data: {
            fileId: fileId
        },
        dataType: "html",
        success: function (response) {

            var results = JSON.parse(response);

            var table_data = '';
            if (results.status == 'success') {
                if (!jQuery.isEmptyObject(results.partners)) {
                    $.each(results.partners, function (key, value) {
                        table_data += '<tr><td>' + value.PartnerID + '</td><td>' + value.PartnerTypeID + '</td><td>' + value.PartnerType.PartnerTypeName + '</td><td>' + value.PartnerName + '</td></tr>';
                    });
                }
                else {
                    table_data += '<tr><td colspan="4" style="text-align: center;">No records found.</td></tr>';
                }
                $('#tbl-partners-data tbody').html(table_data);
                $('#partnersModal').modal('show');
            }
            else if (results.status == 'error') {
                alert(results.msg);
            }
            $('#page-preloader').css('display', 'none');
        }
    });
}

function getRevenueData() {
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    var sales_rep_id = $('#sales_user_filter').val();
    $.ajax({
        url: base_url + "get-revenue-data",
        method: "POST",
        data: {
            sales_rep_id: sales_rep_id
        },
        success: function (data) {
            var result = jQuery.parseJSON(data);
            $('#page-preloader').css('display', 'none');
            if (result.status == 'success') {
                $('#revenue_container').html(result.data);
                $('#revenue_model').modal('show');
            } else {
                $('#revenue_container').html(result.data);
                $('#revenue_model').modal('show');
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            // $('#lp_order_error_msg').html('Something went wrong. Please try it again.').show();
            // $([document.documentElement, document.body]).animate({
            //     scrollTop: $("#lp_order_success_msg").offset().top
            // }, 1000);

            // setTimeout(function () {
            //     $('#lp_order_error_msg').html('').hide();
            // }, 5000);
        }
    });
}

function getRevenueDataBasedOnMonth(month) {
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    var sales_rep_id = $('#sales_user_production_filter').val();
    $.ajax({
        url: base_url + "get-revenue-data",
        method: "POST",
        data: {
            sales_rep_id: sales_rep_id,
            month: month
        },
        success: function (data) {
            var result = jQuery.parseJSON(data);
            $('#page-preloader').css('display', 'none');
            if (result.status == 'success') {
                $('#revenue_container').html(result.data);
                $('#revenue_model').modal('show');
            } else {
                $('#revenue_container').html(result.data);
                $('#revenue_model').modal('show');
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            // $('#lp_order_error_msg').html('Something went wrong. Please try it again.').show();
            // $([document.documentElement, document.body]).animate({
            //     scrollTop: $("#lp_order_success_msg").offset().top
            // }, 1000);

            // setTimeout(function () {
            //     $('#lp_order_error_msg').html('').hide();
            // }, 5000);
        }
    });
}

function displayComment(comments) {
    if (comments.length > 0) {
        // let listHtml = "";
        // commentsArray.forEach(function (comment) {
        //     listHtml += `<li class="list-group-item">${comment}</li>`;
        // });
        // $("#commentList").html(listHtml);
        $("#commentList").html(comments);
        $("#commentModal").modal("show");
    }
}
