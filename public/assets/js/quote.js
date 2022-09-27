$(document).ready(function () {
    if ($('#edit_module').val() == '1') {
        $('#customer_shipping_content').removeClass('d-none');
    }
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function dspPriceFormat(price) {
    return '$' + price.toFixed(2);
}

function dspPriceFormatWithoutDoller(price) {
    return price.toFixed(2);
}

function clearSelectionFields() {
    $(".select2-show-search").select2("val", "[]");
}

function saveEdit(pid) {
    editProduct(pid);
}

$(document).on('change', '#customer_id', function () {
    let userId = $(this).val();
    $('#customer_shipping_content').removeClass('d-none');
});

function generateCartTotal() {
    var subtotal = 0;
    var numItems = 0;

    //Product price calculation
    var taxesWithState = 0;
    var state = $('#select_state').val();
    var stateTax = 0;
    if (state != "") {
        stateTax = 0;
    }

    $("#taxes_cost").val(0);
    $('input.cart_product_id').each(function () {
        _pid = $(this).val();
        t_retail = $("#cart_item_" + _pid + " input.cart_product_retail").val();
        t_qty = $("#cart_item_" + _pid + " input.cart_product_qty").val();
        t_discount = $("#cart_item_" + _pid + " input.cart_product_discount").val();
        t_tax_prod = $("#cart_item_" + _pid + " input.cart_product_tax").val();
        prod_taxable = $("#cart_item_" + _pid + " input.cart_product_taxable").val();
        if (t_discount == '' || isNaN(t_discount)) {
            t_discount = 0;
        }
        t_discount = parseFloat(t_discount);
        _tPrice = parseFloat(parseFloat(t_retail) * parseInt(t_qty));
        // console.log(prod_taxable);
        if (prod_taxable == 1) {
            _tax_prod = parseFloat((t_retail * stateTax) / 100);
            _tTax = parseFloat(parseFloat(_tax_prod) * parseInt(t_qty));
            var preTax = $("#taxes_cost").val();
            var temp = parseFloat(preTax) + _tTax;
            var taxAmountTotall = parseFloat(temp).toFixed(2);
            $("#taxes_cost").val(taxAmountTotall);
        }
        if (t_discount != 0) {
            _tPrice = _tPrice - ((_tPrice * t_discount) / 100);
        }

        $("#cart_item_" + _pid + " td.cart_product_total_price").html(dspPriceFormat(_tPrice));

        subtotal = subtotal + _tPrice;
        numItems = numItems + 1;
    })

    if (numItems != 0) {
        $("#updateTotalButton").show();
    } else {
        $("#updateTotalButton").hide();
    }


    $("#dsp_subtotal").html(dspPriceFormat(subtotal));

    _shipping_cost = parseFloat($("#shipping_cost").val());
    _taxes_cost = parseFloat($("#taxes_cost").val());
    if (isNaN(_shipping_cost)) {
        _shipping_cost = 0;
        $("#shipping_cost").val(dspPriceFormatWithoutDoller(0));
    }
    if (isNaN(_taxes_cost)) {
        _taxes_cost = 0;
        $("#taxes_cost").val(dspPriceFormatWithoutDoller(0));
    }

    _total = subtotal + _shipping_cost + _taxes_cost;

    $("#dsp_total").html(dspPriceFormat(_total));
}


function changeValue(pid) {
    $('#is_changed_' + pid).val(1);
    editProduct(pid);
}

///////////////////////////edit product////////////////////////////
function editProduct(pid) {
    let productId = pid;
    let quantity = $('#quantity_' + pid).val();
    let retail = $('#retail_' + pid).val();
    let discount = $('#discount_' + pid).val();
    let isChanged = $('#is_changed_' + pid).val();
    $.ajax({
        url: HOME_URL + '/get-base-value',
        method: 'get',
        data: {
            'id': productId,
            'quantity': quantity,
            'retail': retail,
            'isChanged': isChanged,
            'discount': discount
        },
        beforeSend: function () {
            $('body').css('pointer-events', 'none');
        },
        success: function (response) {
            let res = response.data;
            let disc = response.discount;
            $('#retail_' + pid).html('$' + res);
            $('#retail_' + pid).val(res);
            $('#discount_' + pid).val(disc);
            let tPrice = parseFloat(parseFloat(res) * parseFloat(quantity));
            $('#product_retail_' + pid).val(res);
            $('#total_' + pid).html(tPrice);
            $('#quantity_' + pid).val(response.quantity);
            generateCartTotal();
            $('#is_changed_' + pid).val(0);
            $('body').css('pointer-events', 'unset');
        },
        error: function () {
            $('body').css('pointer-events', 'unset');
        }
    })
}

///////////////////////////////PRODUCTS//////////////////
function addProductsToCart() {

    var taxesWithState = 0;
    var selectedValues = $('#select_product').val();
    var pQty = 1;
    var state = $('#select_state').val();
    var pdiscount = 0;
    var stateTax = 0;

    $("#select_product option:selected").map(function (i, el) {
        var pTitle = $(el).attr('pTitle');
        var pPartNo = $(el).attr('pPartNo');
        var pRetail = $(el).attr('pRetail');
        var pTaxable = $(el).attr('pTaxable');
        var pAdjustPrice = $(el).attr('pAdjustPrice');
        var pMap = $(el).attr('pMap');
        var pid = $(el).val();
        $.ajax({
            url: HOME_URL + '/get-base-value',
            method: 'get',
            data: {'id': pid, 'quantity': pQty, 'retail': pRetail, 'isChanged': 0, 'discount': 0},
            beforeSend: function () {
                $('body').css('pointer-events', 'none');
            },
            success: function (response) {
                $('body').css('pointer-events', 'unset');
                let res = response.data;
                let status = response.status;
                pQty = response.quantity;
                pRetail = res;
                dsp_pTitle = pTitle;
                if (pPartNo != '') {
                    dsp_pTitle += " - " + pPartNo;
                }
                var tr_id = "cart_item_" + pid;

                tPrice = parseFloat(parseFloat(pRetail) * parseFloat(pQty));

                if (pTaxable == 1) {
                    if (state != "") {
                        stateTax = parseFloat(stateTax) + parseFloat(taxesWithState[state]);
                        // console.log(taxesWithState[state] , state , stateTax);
                    } else {
                        alert('State is required');
                        return 0;
                    }
                }
                stateTax = parseFloat(stateTax);
                var pRetailNew = parseFloat(tPrice);
                var taxFind = (pRetailNew * stateTax) / 100;
                var taxAmount = (parseFloat(pRetail) * stateTax) / 100;
                var taxCost = parseFloat($("#taxes_cost").val());
                taxCost = taxCost + taxFind;
                $('#taxes_cost').val(parseFloat(taxCost).toFixed(2));
                if ($("#" + tr_id).length == 0) {
                    var appText = '<tr id="' + tr_id + '">';
                    appText += '<td>' + dsp_pTitle;
                    appText += '<input type="hidden" class="cart_product_id" name="cart_product_id[]" value="' + pid + '"  />';
                    appText += '<input type="hidden" class="cart_product_retail" id="product_retail_' + pid + '" name="cart_product_retail[]" value="' + pRetail + '"  />';
                    appText += '<input type="hidden" class="cart_product_tax" name="cart_product_tax[]" value="' + taxAmount + '"  />';
                    appText += '<input type="hidden" class="cart_product_taxable" name="cart_product_taxable" value="' + pTaxable + '"  />';
                    appText += '<input type="hidden" id="is_changed_' + pid + '" value="0"/>';
                    appText += '</td>';
                    if (pAdjustPrice == 0) {
                        appText += '<td class="tx-center" id="retail_' + pid + '">$' + pRetail + '</td> ';
                    } else {
                        appText += '<td><div class="tx-center">' + pRetail + '</div></td>';
                    }
                    appText += '<td><div class="tx-center"><input id="quantity_' + pid + '" class="form-control qtyText cart_product_qty" name="cart_product_qty[]" type="number" min="' + pQty + '" step="1" onchange="javascript:editProduct(' + pid + ')" onfocusout="saveEdit(' + pid + ')" onkeypress="return /[0-9]/i.test(event.key);" value="' + pQty + '"  /></div><small><a href="javascript:editProduct(' + pid + ')">Update</a><small> </td>';

                    appText += '<td><div class="tx-center"><input id="discount_' + pid + '" class="form-control qtyText cart_product_discount" name="cart_product_discount[]" type="number" onchange="javascript:editProduct(' + pid + ')" onfocusout="saveEdit(' + pid + ')" min="0" step=".01"  value="' + pdiscount + '"  /></div><small><a href="javascript:editProduct(' + pid + ')">Update</a><small></td>';


                    appText += '<td class="tx-center cart_product_total_price" id="total_' + pid + '" >' + dspPriceFormat(tPrice) + '</td>';
                    appText += '<td class="tx-center"><a href="javascript:removethisproduct(' + pid + ')"><i class="fa fa-trash-alt"></i></a></td>';
                    appText += '</tr>';
                    $('#quotes_products tbody').append(appText);

                } else {
                    _pQty = $("#" + tr_id + " input.cart_product_qty").val();
                    _nQrt = parseInt(_pQty) + parseInt(pQty);
                    $("#" + tr_id + " input.cart_product_qty").val(_nQrt);
                    tPrice = parseFloat(parseFloat(pRetail) * _nQrt);
                    $("#" + tr_id + " .cart_product_total_price").html("$" + tPrice);

                }
                generateCartTotal();
                clearSelectionFields();
            },
            error: function () {
                $('body').css('pointer-events', 'unset');
            }
        });
    }).get();
    $("#product_selection_popup_model").modal('hide');
}


function removethisproduct(pid) {
    var bg = $("#cart_item_" + pid).css('background');
    $("#cart_item_" + pid).css('background', '#FFBCB0');
    setTimeout(function () {
        $("#cart_item_" + pid).css('background', bg);
        $("#cart_item_" + pid).hide();
        $("#cart_item_" + pid).remove();
        generateCartTotal();
    }, 1000);


}

$(function () {
    'use strict';

    generateCartTotal();
    $('.select2-show-search-customer').select2({
        minimumResultsForSearch: ''
    });
    $('.select2-show-search').select2({
        minimumResultsForSearch: ''
    });


});
