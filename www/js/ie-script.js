
alert('HI');
// Document ready function
$(document).ready(function(){
/*** IE Placeholder Fix **/

if($.browser.msie){
   $('input[placeholder]').each(function(){  
        
        var input = $(this);        
        
        $(input).val(input.attr('placeholder'));
                
        $(input).focus(function(){
             if (input.val() == input.attr('placeholder')) {
                 input.val('');
             }
        });

        
        $(input).blur(function(){
            if (input.val() == '' || input.val() == input.attr('placeholder')) {
                input.val(input.attr('placeholder'));
            }
        });
    });
};


/*** IE Placeholder Fix **/

if($.browser.msie){

	alert('HI');
   $('textarea[placeholder]').each(function(){  
        
        var input = $(this);        
        
        $(input).val(input.attr('placeholder'));
                
        $(input).focus(function(){
             if (input.val() == input.attr('placeholder')) {
                 input.val('');
             }
        });

        
        $(input).blur(function(){
            if (input.val() == '' || input.val() == input.attr('placeholder')) {
                input.val(input.attr('placeholder'));
            }
        });
    });
};


}