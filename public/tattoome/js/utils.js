$(function(){
        var waypoint = new Waypoint({
            element: $('#barre-fixe')[0],
            handler: function(direction) {
                if(direction=="down"){
                    $(this.element).addClass('affix-nav');
                }else{
                    $(this.element).removeClass('affix-nav');
                }
            }
        })

        var waypoint2 = new Waypoint({
            element: $('#form_fixed')[0],
            handler: function(direction) {
                if(direction=="down"){
                    $(this.element).addClass('affix');
                }else{
                    $(this.element).removeClass('affix');
                }
            }
        })
});