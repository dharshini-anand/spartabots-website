<div class="wrapper responsive" style="padding:5px;box-sizing:border-box;-moz-box-sizing:border-box">
<?php if (!empty($breadcrumb)):?><div class="breadcrumb"><?php echo $breadcrumb ?></div><?php endif;?>
<script>
function prfAddRow() {
    $('<tr class="prf-req-item new-req-item"> \
        <td><input class="prf-req-vendor" type="text" /></td> \
        <td><input class="prf-req-qty" type="number" onkeypress="return isNumberKey(event)" min="1" pattern="\d*" /></td> \
        <td><input class="prf-req-name" type="text" /></td> \
	<td><input class="prf-req-partNo" type="text" /></td> \
        <td><input class="prf-req-price" type="number" onkeypress="return isNumberKey(event)" min="0" step="0.01" /></td> \
        <td><input class="prf-req-total" type="text" readonly /></td> \
        <td><span onclick="$(this).closest(\'tr\').remove()" href="#" class="entypo-cancel" title="Remove row" style="padding:4px;color:rgb(68,68,68);cursor:pointer"></span></td> \
    </tr>').appendTo('#prf-reqs-main');

    $('.new-req-item .prf-req-qty, .new-req-item .prf-req-price').on('input', function() {
        prfChange();
    });
    $('.new-req-item').removeClass('new-req-item');

    prfChange();
}
function prfAddMultipleRows() {
    var input = prompt('Enter the number of items you want to add.', '1');
    if (input == null) {
        return;
    }

    var times = parseInt(input);
    if (isNaN(times)) {
        alert('You must enter a number.');
    } else {
        if (times > 40) {
            alert('Cannot enter more than 40 at a time.');
            return;
        } else if (times < 1) {
            alert('Must enter at least 1.');
            return;
        }

        for(var i = 0; i < times;  i++) {
            $('<tr class="prf-req-item new-req-item"> \
                <td><input class="prf-req-vendor" type="text" /></td> \
       		<td><input class="prf-req-qty" type="number" onkeypress="return isNumberKey(event)" min="1" pattern="\d*" /></td> \
        	<td><input class="prf-req-name" type="text" /></td> \
		<td><input class="prf-req-partNo" type="text" /></td> \
        	<td><input class="prf-req-price" type="number" onkeypress="return isNumberKey(event)" min="0" step="0.01" /></td> \
        	<td><input class="prf-req-total" type="text" readonly /></td> \
                <td><span onclick="$(this).closest(\'tr\').remove();prfChange();return false" href="#" class="entypo-cancel" title="Remove row" style="padding:4px;color:rgb(68,68,68);cursor:pointer"></span></td> \
            </tr>').appendTo('#prf-reqs-main');
        }
        $('.new-req-item .prf-req-qty, .new-req-item .prf-req-price').on('input', function() {
            prfChange();
        });
        $('.new-req-item').removeClass('new-req-item');

        prfChange();
    }
}
function isNumberKey(evt) {
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode != 46 && charCode > 31
    && (charCode < 48 || charCode > 57))
     return false;

  return true;
}
function prfChange() {
    var subTotal = 0;
    $('.prf-req-item').each(function() {
        var itemTotal = parseFloat($(this).find('.prf-req-qty').val()) * parseFloat($(this).find('.prf-req-price').val());
        if (!isNaN(itemTotal)) {
            $(this).find('.prf-req-total').val('$'+itemTotal.toFixed(2));
            subTotal+=itemTotal;
        } else {
            $(this).find('.prf-req-total').val('');
        }
    });
    $('#prf-reqs-subtotal').val('$'+subTotal.toFixed(2));
}

function prfSubmit() {
    var count = $('.prf-req-item').length;
    if (count == 0) {
        site_alert('Purchase Request Submission Error', 'Must have a least one item.');
        return;
    }

    var items = '';
    $('.prf-req-item').each(function() {
        items += encodeURIComponent($(this).find('.prf-req-vendor').val()) + ',';
        items += encodeURIComponent($(this).find('.prf-req-qty').val()) + ',';
        items += encodeURIComponent($(this).find('.prf-req-name').val()) + ',';
        items += encodeURIComponent($(this).find('.prf-req-partNo').val()) + ',';
        items += encodeURIComponent($(this).find('.prf-req-price').val()) + ';';
    });

    $.post( '/purchase-request', {
        'data': items,
        'owner': $('#prf-owner').val(),
        'dtReq': $('#prf-dtReq').val(),
        'At': $('#prf-At').val()
    }, function(data) {
        site_alert('Purchase Request Submission', data);
    });
}

$(document).ready(function() {
    $('.prf-req-qty, .prf-req-price').on('input', function() {
        prfChange();
    });
});
</script>

<div id="prf-wrapper">
    <h1 style="color:rgb(51,51,51);margin-top:0;border-bottom:1px solid #e3e3e3;padding-bottom:5px;margin-bottom:20px">Purchase Request</h1>
    <noscript><div class="error-message"><b>ERROR</b> JavaScript must be enabled to use this form.</div></noscript>

    <form id="prf" method="POST" onsubmit="prfSubmit(); return false">
        <div style="margin-bottom:40px">
            <div class="prf-field">
                <label for="prf-owner">Requester name:</label>
                <input id="prf-owner" name="prf-owner" type="text" required />
            </div>
            <div class="prf-field">
                <label for="prf-dtReq">Date required:</label>
                <input id="prf-dtReq" name="prf-dtReq" type="text" />
            </div>
        </div>
        <table id="prf-reqs" style="margin-bottom:40px">
            <thead>
                <tr>
                    <th>Vendor</th>
                    <th style="width:80px;">Quantity</th>
                    <th>Item</th>
                    <th style="width:150px;">Part Number / <abbr title="Amazon Stock Number">ASN</abbr></th>
                    <th style="width:80px;">Unit Cost</th>
                    <th style="width:120px;">Total</th>
                    <th style="width:20px"></th>
                </tr>
            </thead>
            <tbody id="prf-reqs-main">
                <tr class="prf-req-item">
                    <td><input class="prf-req-vendor" type="text" /></td>
                    <td><input class="prf-req-qty" type="number" onkeypress="return isNumberKey(event)" min="1" pattern="\d*" /></td>
                    <td><input class="prf-req-name" type="text" /></td>
                    <td><input class="prf-req-partNo" type="text" /></td>
                    <td><input class="prf-req-price" type="number" onkeypress="return isNumberKey(event)" min="0" step="0.01" /></td>
                    <td><input class="prf-req-total" type="text" readonly /></td>
                    <td><span onclick="$(this).closest('tr').remove();prfChange();return false" href="#" class="entypo-cancel" title="Remove this item" style="padding:4px;color:rgb(68,68,68);cursor:pointer"></span></td>
                </tr>
                <tr class="prf-req-item">
                    <td><input class="prf-req-vendor" type="text" /></td>
                    <td><input class="prf-req-qty" type="number" onkeypress="return isNumberKey(event)" min="1" pattern="\d*" /></td>
                    <td><input class="prf-req-name" type="text" /></td>
                    <td><input class="prf-req-partNo" type="text" /></td>
                    <td><input class="prf-req-price" type="number" onkeypress="return isNumberKey(event)" min="0" step="0.01" /></td>
                    <td><input class="prf-req-total" type="text" readonly /></td>
                    <td><span onclick="$(this).closest('tr').remove();prfChange();return false" href="#" class="entypo-cancel" title="Remove this item" style="padding:4px;color:rgb(68,68,68);cursor:pointer"></span></td>
                </tr>
                <tr class="prf-req-item">
                    <td><input class="prf-req-vendor" type="text" /></td>
                    <td><input class="prf-req-qty" type="number" onkeypress="return isNumberKey(event)" min="1" pattern="\d*" /></td>
                    <td><input class="prf-req-name" type="text" /></td>
                    <td><input class="prf-req-partNo" type="text" /></td>
                    <td><input class="prf-req-price" type="number" onkeypress="return isNumberKey(event)" min="0" step="0.01" /></td>
                    <td><input class="prf-req-total" type="text" readonly /></td>
                    <td><span onclick="$(this).closest('tr').remove();prfChange();return false" href="#" class="entypo-cancel" title="Remove this item" style="padding:4px;color:rgb(68,68,68);cursor:pointer"></span></td>
                </tr>
                <tr class="prf-req-item">
                    <td><input class="prf-req-vendor" type="text" /></td>
                    <td><input class="prf-req-qty" type="number" onkeypress="return isNumberKey(event)" min="1" pattern="\d*" /></td>
                    <td><input class="prf-req-name" type="text" /></td>
                    <td><input class="prf-req-partNo" type="text" /></td>
                    <td><input class="prf-req-price" type="number" onkeypress="return isNumberKey(event)" min="0" step="0.01" /></td>
                    <td><input class="prf-req-total" type="text" readonly /></td>
                    <td><span onclick="$(this).closest('tr').remove();prfChange();return false" href="#" class="entypo-cancel" title="Remove this item" style="padding:4px;color:rgb(68,68,68);cursor:pointer"></span></td>
                </tr>
                <tr class="prf-req-item">
                    <td><input class="prf-req-vendor" type="text" /></td>
                    <td><input class="prf-req-qty" type="number" onkeypress="return isNumberKey(event)" min="1" pattern="\d*" /></td>
                    <td><input class="prf-req-name" type="text" /></td>
                    <td><input class="prf-req-partNo" type="text" /></td>
                    <td><input class="prf-req-price" type="number" onkeypress="return isNumberKey(event)" min="0" step="0.01" /></td>
                    <td><input class="prf-req-total" type="text" readonly /></td>
                    <td><span onclick="$(this).closest('tr').remove();prfChange();return false" href="#" class="entypo-cancel" title="Remove this item" style="padding:4px;color:rgb(68,68,68);cursor:pointer"></span></td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td style="border:0;" colspan="4">
                        <a class="prf-a" onclick="prfAddRow();return false" href="#">Add new item</a>
                        <span> | </span>
                        <a class="prf-a" onclick="prfAddMultipleRows();return false" href="#">Add multiple items</a>
                    </td>
                    <td style="border:0;text-align:right;padding-right:4px">Subtotal:</td>
                    <td style="border-left:1px solid #e3e3e3;"><input id="prf-reqs-subtotal" type="text" readonly /></td>
                </tr>
            </tbody>
        </table>

        <h3>Additional instructions (not required)</h3>
        <textarea id="prf-At" name="prf-At" placeholder="Please provide any special handling, purchasing or shipping information here. "></textarea>

        <div class="special-btn">
            <input onclick="prfSubmit()" type="button" value="Send Request">
            <i class="special-btn-icon fa fa-send"></i>
        </div>

        <div style="font-size:15px;margin-top:10px;">
            <span>See purchase request progress </span>
            <a href="https://docs.google.com/spreadsheets/d/1Cfp4u8JACg41CeOlQn71RU_x1YQHT0r6pQgdoJLDI9c/pubhtml#">here</a>
        </div>
    </form>
</div>
</div>