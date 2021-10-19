/*!
* Start Bootstrap - Shop Homepage v5.0.2 (https://startbootstrap.com/template/shop-homepage)
* Copyright 2013-2021 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-shop-homepage/blob/master/LICENSE)
*/
// This file is intentionally blank
// Use this file to add JavaScript to your project


$("input[type='number']").inputSpinner();


function changePrice(constprice){
    document.getElementById('#price').innerHTML = 0;
    var newprice = 0;
    var amt = document.getElementById('#amt').value;
    const price = constprice;
    newprice = price * amt;
    document.getElementById('#price').innerHTML = 'Price: ₱ <b>'+Math.abs(newprice).toFixed(2)+'</b>';
}


function changePricewTotal(id, product_price){

    var newprice = 0;

    var price = document.getElementById('#price-'+id).innerHTML;

    document.getElementById('#price-'+id).innerHTML = 0; //initialize price element to 0
    
    var amt = document.getElementById('#amt-'+id).value; // 

    newprice = product_price * amt; // order total multiplied by the amount
    
    document.getElementById('#price-'+id).innerHTML = Math.abs(newprice).toFixed(2); //set price of item to new price

    var grand_total = document.getElementById('#total').innerHTML;

    grand_total = (grand_total - price) + newprice;

    document.getElementById('#total').innerHTML = Math.abs(grand_total).toFixed(2);

}

//Makes the rating selection more dynamic
function changeStars(){

    document.getElementById('show_stars').innerHTML = '';

    var select = document.getElementById('stars');
    var value = select.options[select.selectedIndex].value;

    var stars = '';

    for (let i = 0; i < value; i++) {
        stars += '<i style="color:orange;" class="bi bi-star-fill"></i> ';
    } 
    
    document.getElementById('show_stars').innerHTML = stars;

}

//Displays the file name of chosen file in file input
$(function() {

    $("input").on("change", function() {
        document.getElementById('file_path').innerHTML = '';
        var file = this.files;
        document.getElementById('file_path').innerHTML = file[0].name;
    });

});


// //ajax search funcionality
// $(document).ready(function(){
//     $('#order_search').on('keyup', function(){

//         var term=$(this).val();
//         var token = $('meta[name="csrf-token"]').attr('content');
        
//         $.ajax({
//             method:'POST',
//             url:'/orders/search',
//             dataType: 'json',
//             data: {
//                 '_token': token,
//                 term: term,  
//             },
//             success: function(result){
//                 var tableRow ='';

//                     $('#dynamic-row').html('');

//                     $.each(result, function(index, value){
//                         console.log(value.id)
//                     })
//             }
//         })

//     });
//     //end of ajax call
// });
