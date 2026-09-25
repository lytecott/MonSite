$(function(){
    var $mainMenuItems = $("#main-menu ul").children("li"), 
    totalMainMenuItems = $mainMenuItems.length, opendIndex = 2,

    init = function(){
       bindEvents();
       if(validIndex(opendIndex))
            animateItem($mainMenuItems.eq(opendIndex),true,700);
    },
    bindEvents = function(){
        $mainMenuItems.children(".images").click(function(){

            var newIndex = $(this).parent().index();
            checkAndAnimateItem(newIndex);
        });

        $(".button").hover(function(){
            $(this).addClass("hovered");
        },
        function(){
            $(this).removeClass("hovered");

        });

        $(".button").click(function(){
            var newIndex = $(this).index();
            checkAndAnimateItem(newIndex);
        });
    },
    validIndex = function(indexToCheck){
        return(indexToCheck >= 0) && (indexToCheck < totalMainMenuItems);
    },

    animateItem = function($item, toOpen,speed){
            var $colorImage = $item.find(".color"),
            itemParam = (toOpen)? {width: "420px"} : {width: "140px"},
            colorImageParam = toOpen ? {left: "0px"}:{left: "140px"};
            

            $colorImage.animate(colorImageParam,speed);
            $item.animate(itemParam, speed);
    },
    
    checkAndAnimateItem = function(indexToCheckAndAnimate){
            if(opendIndex === indexToCheckAndAnimate)
            {
                animateItem($mainMenuItems.eq(indexToCheckAndAnimate),false,250);
                opendIndex = -1;
            }
            else
            {
                if(validIndex(indexToCheckAndAnimate)){
                    animateItem($mainMenuItems.eq(opendIndex),false,250);
                    opendIndex = indexToCheckAndAnimate;
                    animateItem($mainMenuItems.eq(opendIndex),true,250);
                }
            }
    };

    init();
});
