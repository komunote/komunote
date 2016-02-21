$(document).ready(function(){                
    $('#accordeonMatching').accordion({autoHeight:false,animated: 'bounceslide',collapsible: true});        
    $('input:text').click(function(){$(this).select()});  

    $('[name^="modaleFilter"]').click(function(){                            
                
        $($(this).attr('link')).dialog({            
            modal: true,show: 'slide',width:480,
            buttons: {                
                'Ok': function() {

                    var prix_min =$("#prix_min");
                    var prix_max =$("#prix_max");
                                                            
                    if(!isEmpty(prix_min.val()) && prix_min.val()> prix_max.val()){
                        prix_min.val(0);
                    }
                    $('#filters').val(
                        'price_'+$("input[name='orderby_price']:checked").val()+
                        '__date_'+$("input[name='orderby_date']:checked").val()+
                        (!isEmpty(prix_min.val())? ('__priceGt_'+prix_min.val()):'')+
                        (!isEmpty(prix_max.val())? ('__priceLt_'+prix_max.val()):''));
                    $('#searchForm').submit();
                }                
            }});
    });
});