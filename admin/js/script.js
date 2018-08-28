/* nav bar toggling */
function toggleNavBar() {
    var barwidth = $('.bar').css('width');
    // alert(barwidth);
    if (barwidth === '0px') {
        /*        var w = 15*$(window).width()/100;
                var chartwidth = $(window).width() - 12*$(window).width()/100;*/
        /*
                $('.chart-container').css({
                    'width':chartwidth+'px'
                });*/
        $('.bar').css('width', '26.5%');
        $('.bar').css('padding', '2% 5px');
        $('.bar *').css('opacity', '1');
    }
    else {
        $('.bar').css('width', '0px');
        $('.bar').css('padding', '0');
        $('.bar *').css('opacity', '0');

        /*$('.chart-container').css({
            'width':'100%'
        });*/
    }
    // $('#myChart').attr('height','400px');


}

function load(name,link) {
    $('#main').css('filter','blur(3px)');
    infoLoading("Chargement: "+name+"...");
    setTimeout(function () {
        $('#main').load(link, function () {
            $('#main').css('filter','none');
            info("Chargement terminé...", 350);
        });
    }, 1360);

}

/* show info */

function info(message, delay) {
    $('.info').html(Date().split(" ")[4] + ": " + message);
    $('#infoConcurrence').hide();
    setTimeout(function () {
        $('.info').slideDown(function () {
            if (delay >= 0) {
                $('.info').delay(delay).slideUp(360, function () {
                    $('#infoConcurrence').show();
                });
            }
        });
    }, 500);


}

function closeInfo(delay) {
    $('.info').delay(delay).slideUp(360, function () {
    });
}

function infoLoading(message) {
    info(" &nbsp;<i class='fa fa-spinner animated infinite rotateIn'></i> " + message, -1);
}

/* document ready*/
$(document).ready(function () {
    toggleNavBar();
    $('.modal').modal();
    $('.tooltipped').tooltip({
        delay: 50
    });
    // $('.datefield').pickadate({});
    $('.mm22e0').click(function () {
        load('Tableau de bord',$(this).attr('data-link'));
        $('.list-style > div').css('background', 'rgb(66, 85, 105)');
        $('.list-style1 > div').css('background','white');
        $('.lbc0 > div').css('background', 'rgb(66, 85, 105)');
        $('.lbc3 > div').css('background', 'rgb(66, 85, 105)');

        $('.menucontent1').removeClass('menucontent1');
        $(this).addClass('menucontent1');
        /*.list-style > div{
          background: rgb(66, 85, 105);
      }*/
    });
    $('.mm22e1').click(function () {
        load('Partenaires',$(this).attr('data-link'));
        $('.list-style > div').css('background', 'rgb(66, 85, 105)');
        $('.list-style1 > div').css('background','rgb(66, 85, 105)');
        $('.lbc0 > div').css('background', 'white');
        $('.lbc3 > div').css('background', 'rgb(66, 85, 105)');

        $('.menucontent1').removeClass('menucontent1');
        $(this).addClass('menucontent1');
        /*.list-style > div{
          background: rgb(66, 85, 105);
      }*/
    });
    /*the operational*/
    $('.mm22e2').click(function () {
        load('Transactions',$(this).attr('data-link'));
        $('.lbc1 > div').css('background', 'white');
        $('.list-style1 > div').css('background', 'rgb(66, 85, 105)');
        $('.lbc2 > div').css('background', 'rgb(66, 85, 105)');
        $('.lbc0 > div').css('background', 'rgb(66, 85, 105)');        $('.lbc3 > div').css('background', 'rgb(66, 85, 105)');

        $('.menucontent1').removeClass('menucontent1');
        $(this).addClass('menucontent1');
    });

    $('.mm22e3').click(function () {
        load('',$(this).attr('data-link'));
        $('.lbc2 > div').css('background', 'white');
        $('.list-style1 > div').css('background', 'rgb(66, 85, 105)');
        $('.lbc1 > div').css('background', 'rgb(66, 85, 105)');
        $('.lbc3 > div').css('background', 'rgb(66, 85, 105)');
        $('.lbc0 > div').css('background', 'rgb(66, 85, 105)');
        $('.menucontent1').removeClass('menucontent1');
        $(this).addClass('menucontent1');
    });

    $('.m223e').click(function () {
        load('Parametres',$(this).attr('data-link'));
        $('.lbc2 > div').css('background', 'rgb(66, 85, 105)');
        $('.lbc3 > div').css('background', 'white');
        $('.list-style1 > div').css('background', 'rgb(66, 85, 105)');
        $('.lbc1 > div').css('background', 'rgb(66, 85, 105)');
        $('.lbc0 > div').css('background', 'rgb(66, 85, 105)');
        $('.menucontent1').removeClass('menucontent1');
        $(this).addClass('menucontent1');
    });
    // loadDashboard();
    // document.getElementById('myChart').style.width = '100%';
    // document.getElementById('myChart').style.height = '400px';
});

/* search with criteria section*/

function searchWithCriteriaInitiator(val) {//info("Closed",360);
   /* if (val !== 6 && val !== 2) {
        info("<span>&nbsp;<input type='text' placeholder='Rechercher' id='queryboy' style='max-width: 250px;text-indent: 10px;'><a href='#!' onclick='closeInfo(360);'><i class='fa fa-times-circle'></i></a></span>", -1);
    } else if (val === 2) {
        info("<span>&nbsp;<input type='text' placeholder='Rechercher' id='queryboy' style='max-width: 250px;text-indent: 10px;'><a href='#!' onclick='closeInfo(360);'><i class='fa fa-times-circle'></i></a></span>", -1);
        $('#queryboy').pickadate({});
    } else if (val === 6) {
        info("<span><a href='#!'><i class='fa fa-thumbs-down red-text'></i></a>&nbsp;<a href='#!'><i class='fa fa-thumbs-up light-green-text'></i></a></span>", -1);
    }*/
}