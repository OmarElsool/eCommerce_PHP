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

    // Login / Signup form switch
    $('.login-page h1 span').click(function(){
        $(this).addClass('selected').siblings().removeClass('selected');

        $('.login-page form').hide();

        $('.' + $(this).data('class')).fadeIn(200);
    })


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

    // live writing ad prev
    $('.live-name').keyup(function(){
        $('.live-prev .caption h3').text($(this).val());
    })
    // live writing ad prev
    $('.live-desc').keyup(function(){
        $('.live-prev .caption p').text($(this).val());
    })
    // live writing ad prev
    $('.live-price').keyup(function(){
        $('.live-prev .caption span').text('$' + $(this).val());
    })
})


