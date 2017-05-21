$(window).load(function () {
    //Action for the right container hidder button
    $("#rightcontainerhide").click(function ()
    {
        //Slide away the right container
        $("#rightcontainerheader").hide('slide', {
            direction: 'right',
            easing: 'easeInOutExpo'
        }, 1000);
    });



    //Set the resize canvas function to resize when window is resized
    $(window).resize(function () {
        //Reset the width and height set manually 
        $('#infovis').css({
            width: ''
        });

        console.log($('#infovis').width());

        //Set the canvas width to the new width
        $('#infovis').css({
            width: $('#infovis').width()
        });
        var infovisWidth = $("#infovis").width();
        var infovisHeight = $("#infovis").height();
        tree.canvas.resize(infovisWidth, infovisHeight);
    });

    screenWidth = $(window).width();
    screenHeight = $(window).height();
    //Current window size
    //40, 62 offset is manually calculated

    if (screenWidth <= 360 && screenHeight <= 640) //Probably mobile
    {
        $("#rightcontainerheader").on('swiperight', function () {
            $("#rightcontainerheader").hide('slide', {
                direction: 'right',
                easing: 'easeInOutExpo'
            }, 1000);
        });

        //Height according to screen size
        // infovisHeight = 450;

        //Resize the canvas to fit in window
        //tree.canvas.resize($(window).width() - 40, $(window).height() - 62);

    }

    //Since it is fluid let it adjust and then calculate the height and width and document be ready
    var infovisWidth = $("#infovis").width();

    //Set this manually.
    $("#infovis").css({
        width: infovisWidth,
        height: '80vh'
    });

    //Check if to show the sidebar or not

    //Now init the spacetree and let it think that width and height is constant
    init();
});