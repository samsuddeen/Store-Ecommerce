<script>
    var finalAttributeData=@json($attributeData);
    var productId="{{@$productId}}";
    $(document).on('click','.updateAttribute',function(){
        
        var productId=$(this).attr('data-productId');
        var stockId=$(this).attr('data-stockId');
        var colorCode=$(this).attr('data-colorCode');
        var price=$(this).attr('data-productPrice');
        var totalQuantity=$(this).attr('data-totalquantity');
        $('.decreaseQty').attr('data-stockquantity',1);
        $('.stock_qty').val(1);
        $('.qtyValue').val(1);
        $('.increaseQty').attr('data-id',totalQuantity);
        $('.increaseQty').attr('data-stockquantity',totalQuantity);
        $('.varient_id_class').val(stockId);
        $('.original_price').text(`Rs ${price}`);
        $(`.updateAttribute`).css('border', '2px solid black');
        $(`.selecteField${stockId}`).css('border', '2px solid red');
    });
    $(document).on('click','.changeColorAttribute',function(){
        var colorCode=$(this).attr('data-colorCode');
        var filterData=finalAttributeData.finalAttribute[colorCode];
        console.log(`Final Data Value ${filterData.attributes[0]}`);
        if(filterData.length <= 0){
            alert('Something Went Wrong !!');
            return false;
        }
        $('.decreaseQty').attr('data-stockquantity',1);
        $('.stock_qty').val(1);
        $('.qtyValue').val(1);
        $('.increaseQty').attr('data-id',filterData.attributes[0].totalQty);
        $('.increaseQty').attr('data-stockquantity',filterData.attributes[0].totalQty);
        // formattedNepaliNumber
        var finalPriceValueData=(filterData.attributes[0].delPrice !=null) ? filterData.attributes[0].delPrice  : filterData.attributes[0].price
        $('.original_price').text(`Rs ${finalPriceValueData}`);
        $('.special_price').text(`Rs ${filterData.attributes[0].price}`);
        $('.varient_id_class').val(filterData.attributes[0].stock_id);
        $.ajax({
            url:"{{route('updateAttributeData')}}",
            type:"get",
            data:{
                filterData:filterData,
                stocks:finalAttributeData.stockKeys,
                productId:productId,
                colorData:finalAttributeData.colorsFinalData,
                colorCode:colorCode
            },
            success:function(response){
                $('#attribute').replaceWith(response);
                // $(this).addClass('active');
            }
        })
    });
</script>