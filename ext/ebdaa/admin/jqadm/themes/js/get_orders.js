// get all filter input values for customer order received
function getOrderSearchValues(element_id) {
    let inputs = $('#' + element_id + ' :input')

    let params = {}

    $(inputs).each(function () {
        let input = $(this);
        params[input.attr('name')] = input.val()
    });

    return params
}

//get table body payment statuses by table head select tag
function getPaymentStatus(val) {
    return $('select[name=statuspayment] option[value="'+val+'"]').text();
}

//get table body delivery statuses by table head select tag
function getDeliveryStatus(val) {
    return $('select[name=statusdelivery] option[value="'+val+'"]').text();
}

//get orders
function getVendorOrders(url){

    // remove all orders before set new
    $('#order_received_table_body').children()
        .not(':nth-child(1)')
        .not(':nth-child(2)')
        .not(':nth-child(3)')
        .remove();

    let site_code = $('input[name=site_code]').val()
    //send get request
    $.ajax({
        url: url,
        method: 'get',
        data: getOrderSearchValues('order_received_table_body'),
        dataType: 'json',
        success: function (result) {
            //set pagination values
            $("a[aria-label=vendor-orders-next]").attr('value', result.data.next_page_url !== null ? result.data.next_page_url : result.data.last_page_url)
            $("a[aria-label=vendor-orders-previous]").attr('value', result.data.prev_page_url !== undefined ? result.data.first_page_url : 1)
            $("a[aria-label=vendor-orders-last]").attr('value', result.data.last_page_url)
            $("a[aria-label=vendor-orders-first]").attr('value', result.data.first_page_url)

            $('.current_page').html(result.data.current_page)
            $('.last_page').html(result.data.last_page)
            result.data.data.forEach(function (val, index) {
                // generate html for table
                let text = ''

                text +=
                    '<tr>' +
                        '<td>' +
                            '<a class="items-field" href="/admin/'+site_code+'/jqadm/get/order/'+val['baseid']+'?lang=en" tabindex="1">'
                                + val['invoiceId'] +
                            '</a>' +
                        '</td>'+
                        '<td>' +
                            '<a class="items-field" href="/admin/'+site_code+'/jqadm/get/order/'+val['baseid']+'?lang=en" tabindex="1">'
                                + val['baseid'] +
                            '</a>' +
                        '</td>'+
                        '<td>' +
                            '<a class="items-field" href="/admin/'+site_code+'/jqadm/get/order/'+val['baseid']+'?lang=en" tabindex="1">'
                                + getPaymentStatus(val['statuspayment']) +
                            '</a>' +
                        '</td>'+
                        '<td>' +
                            '<a class="items-field" href="/admin/'+site_code+'/jqadm/get/order/'+val['baseid']+'?lang=en" tabindex="1">'
                                + getDeliveryStatus(val['statusdelivery']) +
                            '</a>' +
                        '</td>'+
                        '<td>' +
                            '<a class="items-field" href="/admin/'+site_code+'/jqadm/get/order/'+val['baseid']+'?lang=en" tabindex="1">'
                                + val['cdate'] +
                            '</a>' +
                        '</td>'+'' +
                        '<td>' +
                        '<a class="items-field" href="/admin/'+site_code+'/jqadm/get/order/'+val['baseid']+'?lang=en" tabindex="1">'
                        + val['cdate'] +
                        '</a>' +
                        '</td>'+'' +
                        '<td>' +
                            '<a class="items-field" href="/admin/'+site_code+'/jqadm/get/order/'+val['baseid']+'?lang=en" tabindex="1">'
                                + val['sitecode'] +
                            '</a>' +
                        '</td>'+
                        '<td>' +
                            '<a class="items-field" href="/admin/'+site_code+'/jqadm/get/order/'+val['baseid']+'?lang=en" tabindex="1">'
                                + val['lastname'] +
                            '</a>' +
                        '</td>'+
                    '</tr>'

                $('#order_received_table_body').append(text)


            })
        },
        error: function (data) {
            console.log(data);
        }
    });
}

//get orders by search click
$('.orders_received').click(function (e) {
    e.preventDefault()
    let current_page = '/vendor/orders/?page=' + $('.current_page').text()
    getVendorOrders(current_page)
})

// check url in customer page get orders
let pattern = new RegExp('get/customer')
let url = window.location.href

let is_customer_page = pattern.test(url)

if(is_customer_page){
    //get orders when start page
    getVendorOrders('/vendor/orders/?page=1')
}

//export excel sheet
$('.export-orders-excel').click(function (e) {
    e.preventDefault()

    let str = '';

    for (let key in getOrderSearchValues('order_received_table_body')) {
        if (str !== "") {
            str += "&";
        }
        str += key + "=" + getOrderSearchValues('order_received_table_body')[key];
    }
    let url = '/orders/vendor/excel/?' + str

    location.href = url

})


$('.all-orders-download-excel').click(function (e) {
    e.preventDefault()

    let str = '';

    for (let key in getOrderSearchValues('orders-list-tbody')) {
        if (str !== "") {
            str += "&";
        }
        str += key + "=" + getOrderSearchValues('orders-list-tbody')[key];
    }

    url = '/orders/excel/?' + str
    location.href = url
})

$('input[name="orders-ctime"]').change(function() {

    Date.prototype.addDays = function(days) {
        var date = new Date(this.valueOf());
        date.setDate(date.getDate() + days);
        return date;
    }

    let date = new Date($(this).val());

    Date.prototype.yyyymmdd = function() {
        var mm = this.getMonth() + 1; // getMonth() is zero-based
        var dd = this.getDate();

        return [this.getFullYear(),
            (mm>9 ? '' : '0') + mm,
            (dd>9 ? '' : '0') + dd
        ].join('-');
    };

    $(this).val(date.yyyymmdd())

    $('#end_date_input').val(date.addDays(1).yyyymmdd())
});
