$(window).load(function () {
    //Set the resize canvas function to resize when window is resized
    $(window).resize(function () {


    });

    screenWidth = $(window).width();
    screenHeight = $(window).height();
    //Current window size
    //40, 62 offset is manually calculated

    if (screenWidth <= 360 && screenHeight <= 640) //Probably mobile
    {
        $("#leftcontainerheader").removeClass('span8').addClass('span12');
        $('#rightcontainerheader').removeClass('span4').addClass('span12');

        //Height according to screen size
        infovisHeight = 450;

        //Resize the canvas to fit in window
        //tree.canvas.resize($(window).width() - 40, $(window).height() - 62);

    }
    
    //Since it is fluid let it adjust and then calculate the height and width and document be ready
    var infovisWidth = $("#infovis").width();

    //Set this manually.
    $("#infovis").css({
        width: infovisWidth,
        height: infovisHeight
    });

    //Check if to show the sidebar or not

    //Now init the spacetree and let it think that width and height is constant
    init();
});