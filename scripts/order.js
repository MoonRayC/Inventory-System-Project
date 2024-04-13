function addRow() {

	var tableLength = $("#productTable tbody tr").length;

	var tableRow;
	var arrayNumber;
	var count;

	if (tableLength > 0) {
		tableRow = $("#productTable tbody tr:last").attr('id');
		arrayNumber = $("#productTable tbody tr:last").attr('class');
		count = tableRow.substring(3);
		count = Number(count) + 1;
		arrayNumber = Number(arrayNumber) + 1;
	} else {
		// no table row
		count = 1;
		arrayNumber = 0;
	}

	$.ajax({
		url: 'action/fetchProductData.php',
		type: 'post',
		dataType: 'json',
		success:function(response) {
			$("#addRowBtn").button("reset");			

			var tr = '<tr id="row'+count+'" class="'+arrayNumber+'">'+			  				
				'<td>'+
					'<div class="form-group">'+

					'<select class="form-control" name="productName[]" id="productName'+count+'" onchange="getProductData('+count+')" >'+
						'<option value="">~~SELECT~~</option>';
						$.each(response, function(index, value) {
							tr += '<option value="'+value[0]+'">'+value[1]+'</option>';							
						});
            
                        tr += '</select>' +
                            '</div>' +
                        '</td>' +
                        '<td>' +
                            '<input type="text" name="price[]" id="price' + count + '" ' +
                                'autocomplete="off" disabled="true" class="form-control" />' +
                            '<input type="hidden" name="priceValue[]" ' +
                                'id="priceValue' + count + '" autocomplete="off" class="form-control" />' +
                        '</td>' +
                        '<td style="padding-left:20px;">' +
                            '<div class="form-group">' +
                                '<p id="stocks' + count + '"></p>' +
                            '</div>' +
                        '</td>' +
                        '<td>' +
                            '<div class="form-group">' +
                                '<input type="number" name="quantity[]" id="quantity' + count + '" ' +
                                    'onkeyup="getTotal(' + count + ')" autocomplete="off" class="form-control" min="1" />' +
                            '</div>' +
                        '</td>' +
                        '<td>' +
                            '<input type="text" name="total[]" id="total' + count + '" ' +
                                'autocomplete="off" class="form-control" disabled="true" />' +
                            '<input type="hidden" name="totalValue[]" ' +
                                'id="totalValue' + count + '" autocomplete="off" class="form-control" />' +
                        '</td>' +
						'<td>' +
                            '<button type="button" class="btn btn-default" onclick="addRow()" id="addRowBtn">' +
                                '<svg width="20" height="20" fill="currentColor">' +
                                    '<use xlink:href="#add" />' +
                                '</svg>' +
                            '</button>' +
                        '</td>' +
                        '<td>' +
                            '<button type="button" class="btn btn-default removeProductRowBtn" ' +
                                'id="removeProductRowBtn" onclick="removeProductRow(' + count + ')">' +
                                '<svg width="20" height="20" fill="currentColor">' +
                                    '<use xlink:href="#delete" />' +
                                '</svg>' +
                            '</button>' +
                        '</td>' +
                    '</tr>';
			if (tableLength > 0) {
				$("#productTable tbody tr:last").after(tr);
			} else {
				$("#productTable tbody").append(tr);
			}

		} // /success
	});	// get the product data

} // /add row

function removeProductRow(row = null) {
    var tableLength = $("#productTable tbody tr").length;

    if (row && tableLength > 1) {
        $("#row" + row).remove();
        subAmount();
    } 
}


function getProductData(row = null) {

	if (row) {
		var productID = $("#productName" + row).val();

		if (productID == "") {
			$("#price" + row).val("");

			$("#quantity" + row).val("");
			$("#total" + row).val("");

		} else {
			$.ajax({
				url: 'action/fetchSelectedProduct.php',
				type: 'post',
				data: { productID: productID },
				dataType: 'json',
				success: function (response) {
					// setting the rate value into the rate input field

					$("#price" + row).val(response.price);
					$("#priceValue" + row).val(response.price);

					$("#quantity" + row).val(1);
					$("#stocks" + row).text(response.stocks);

					var total = Number(response.price) * 1;
					total = total.toFixed(2);
					$("#total" + row).val(total);
					$("#totalValue" + row).val(total);

					subAmount()
				} // /success
			}); // /ajax function to fetch the product data	
		}

	} else {
		alert('no row! please refresh the page');
	}
} // /select on product data

function getTotal(row = null) {
	if (row) {
		var total = Number($("#price" + row).val()) * Number($("#quantity" + row).val());
		total = total.toFixed(2);
		$("#total" + row).val(total);
		$("#totalValue" + row).val(total);

		subAmount()

	} else {
		alert('no row !! please refresh the page');
	}
}

function subAmount() {
	var tableProductLength = $("#productTable tbody tr").length;
	var totalSubAmount = 0;
	for(x = 0; x < tableProductLength; x++) {
		var tr = $("#productTable tbody tr")[x];
		var count = $(tr).attr('id');
		count = count.substring(3);

		totalSubAmount = Number(totalSubAmount) + Number($("#total"+count).val());
	} // /for

	totalSubAmount = totalSubAmount.toFixed(2);

	//total Amount
	$("#subTotal").val(totalSubAmount);
	$("#subTotalValue").val(totalSubAmount);

	var discount = $("#discount").val();
	if(discount) {
		var grandTotal = Number($("#subTotal").val()) - Number(discount);
		grandTotal = grandTotal.toFixed(2);
		$("#grandTotal").val(grandTotal);
		$("#grandTotalValue").val(grandTotal);
	} else {
		$("#grandTotal").val(totalSubAmount);
		$("#grandTotalValue").val(totalSubAmount);
	} // /else discount	

	var paidAmount = $("#paid").val();
	if(paidAmount) {
		paidAmount =  Number($("#grandTotal").val()) - Number(paidAmount);
		paidAmount = paidAmount.toFixed(2);
		$("#due").val(paidAmount);
		$("#dueValue").val(paidAmount);
	} else {	
		$("#due").val($("#grandTotal").val());
		$("#dueValue").val($("#grandTotal").val());
	} // else

} // /sub total amount

function discountFunc() {
	var discount = $("#discount").val();
 	var totalAmount = Number($("#subTotal").val());
 	totalAmount = totalAmount.toFixed(2);

 	var grandTotal;
 	if(totalAmount) { 	
 		grandTotal = Number($("#subTotal").val()) - Number($("#discount").val());
 		grandTotal = grandTotal.toFixed(2);

 		$("#grandTotal").val(grandTotal);
 		$("#grandTotalValue").val(grandTotal);
 	} else {
 	}

 	var paid = $("#paid").val();

 	var dueAmount; 	
 	if(paid) {
 		dueAmount = Number($("#grandTotal").val()) - Number($("#paid").val());
 		dueAmount = dueAmount.toFixed(2);

 		$("#due").val(dueAmount);
 		$("#dueValue").val(dueAmount);
 	} else {
 		$("#due").val($("#grandTotal").val());
 		$("#dueValue").val($("#grandTotal").val());
 	}

} // /discount function

function paidAmount() {
	var grandTotal = $("#grandTotal").val();

	if(grandTotal) {
		var dueAmount = Number($("#grandTotal").val()) - Number($("#paid").val());
		dueAmount = dueAmount.toFixed(2);
		var dueAmountP = Math.max(0, dueAmount);
		var changeAmount = dueAmount < 0 ? Math.abs(dueAmount) : 0;

		$("#due").val(dueAmountP);
		$("#dueValue").val(dueAmountP);
		$("#change").val(changeAmount);
		$("#changeValue").val(changeAmount);
	} // /if
} // /paid amoutn function