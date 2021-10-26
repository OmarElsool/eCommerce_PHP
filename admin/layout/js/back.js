let input = document.querySelectorAll('input');
let pass = document.getElementById('Password');


input.forEach(element => {
    // to put * after the input field that has required attr
    if(element.hasAttribute('required')){
    element.insertAdjacentHTML("afterend",
    '<span class="asterisk">*</span>');
    }

    // to put the eye after the password field
    if(element.type === 'password'){
        element.insertAdjacentHTML("afterend",
        '<i class="show-pass bi bi-eye"></i>');
    }
});

$(function(){
    'use strict';

    var passField = $('.password')
    // convert pass field to text on hover
    $('.show-pass').hover(function(){

        passField.attr('type' , 'text');

    },function(){
        
        passField.attr('type','password');

    });

    // confirm on delete button
    $('.confirm').click(function(){

        return confirm('Are You Sure?!');
    
    })

    // Dashboard
    $('.toggle-info').click(function(){
        $(this).toggleClass('selected').parent().next('.card-body').fadeToggle(100);

        if($(this).hasClass('selected')){
            $(this).html('<i class="bi bi-plus-lg"></i>');
        }else{
            $(this).html('<i class="bi bi-dash-lg"></i>');
        }
    })

})


