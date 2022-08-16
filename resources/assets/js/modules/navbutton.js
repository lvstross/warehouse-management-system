$(document).ready(function(){
    /**
     * Boolean value for opening and closing the left navigation
     * @var
     */
    let navToggle = false;

    /**
     * Boolean value for changing between fullscreen or centered screen
     * @var
     */
    let fullPage = false;
    
    /**
     * Click event for changing the styling of the right navigation
     * @eventListener
     */
    $('#inner-toggle').on('click', function(){
        if(navToggle){
            $('.side-nav').css('left', '0');
            $('#toggle-btn').css('left', '0');
            navToggle = false;
        }else{
            $('.side-nav').css('left', '225px');
            $('#toggle-btn').css('left', '225px');
            navToggle = true;
        }
    });

    /**
     * Click event for changing the class of the main container
     * @eventListener
     */
    $('#inner-toggle-page').on('click', function(){
        if(fullPage){
            $('#main-container').removeClass('container-fluid');
            $('#main-container').addClass('container');
            fullPage = false;
        }else{
            $('#main-container').addClass('container-fluid');
            $('#main-container').removeClass('container');
            fullPage = true;
        }
    });
});
