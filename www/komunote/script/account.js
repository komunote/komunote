generateAccount=function(data,t){
  for(var j in data){
    for(var i in data[j].rows){
      var row=data[j].rows[i];
      var ttc=Math.round((parseFloat(row.unit_price)+parseFloat(row.unit_shipping_price))*parseFloat(row.quantity)*100)/100;
      ttc = ttc.toString().match(/^\d+(?:\.\d{0,2})?/);
      var h3=$('<h3><a href="#">'+row.keywords.toString().substr(0, 20)+"... - "+row.date_created+' - '+ttc+' &euro;</a></h3>');
      var div=$('<div/>');
      var infoBlock=$('<p>'+t.Vendeur[j]+' '+row.pseudo+'</p><img src="images/shop/'+row.id_item+'/'+row.id_item+'.jpg" style="width:128px;height:auto;" onerror="this.src=\'images/komunote2.gif\'"/><p>'+row.keywords+'</p><p>'+row.description+'</p><p><span class="fL cB">'+t.PU[j]+' </span><span class="fR">'+row.unit_price+' &euro;</span></p><p><span class="fL cB">'+t.FDP[j]+' </span><span class="fR">'+row.unit_shipping_price+' &euro;</span></p><p><span class="fL cB">'+t.Qte[j]+' </span><span class="fR">'+row.quantity+'</span></p>');
      var orderBlock=$('<div/>');
      var ratingBlock=$('<div/>');
      var item={
        'user':{
          'pseudo':row.pseudo
          },
        'item':{
          'id':row.id_item,
          'description':row.description
          },
        'order':{
          'id':row.id,
          'id_user':row.id_user,
          'id_seller':row.id_seller
          },
        'rating':{
          'score':row.score,
          'comment':row.comment,
          'answer':row.answer
          }
        };
      
      if(row.is_validated=='1'){
        orderBlock.append('<p><span class="fL cB">'+t.DatePaiement[0]+' </span><span class="fR">'+row.date_updated+'</span></p>');
        if(isEmpty(row.comment)){
          if(row.is_seller==0){
            orderBlock.append($('<form id="form-'+row.id+'" action="'+t.UrlEV[0]+'" method="POST" />').append($('<input type="hidden" name="item" />').val(JSON.stringify(item))).append('<span class="fL cB"><input type="submit" value="'+t.DonnerAvis[0]+'" /></span>'));
          }
        }
      
        var lbReponse=t.MaReponse[1];
        var lbAvis=t.SonAvis[1];
        if(row.is_seller==1){
          lbAvis=t.SonAvis[0];
          lbReponse=t.MaReponse[0];
        }

        if(isNotEmpty(row.comment)){
          var tmp=$('<div />').append($('<hr class="fL cB" style="width:100%;"/><div><p><span class="fL cB" name="myRating">'+lbAvis+' '+row.score+' %</span><span class="fR">'+row.date_comment+'</span></p><p><span class="fL cB">'+row.comment+'</span></p></div>'));
          if(isNotEmpty(row.answer)){
            tmp.append('<p><span class="fL cB" name="hisRating">'+lbReponse+'</span><span class="fR">'+row.date_answer+'</span></p><p><span class="fL cB">'+row.answer+'</span></p>');
          }else if(row.is_seller==1){
            tmp.append($('<form id="form-answer'+row.id+'" action="'+t.UrlEV[j]+'" method="POST" />').append($('<input type="hidden" name="item" />').val(JSON.stringify(item))).append('<p><span class="fL cB"><input type="submit" value="'+t.Repondre[0]+'" /></span></p>'));
          }

          ratingBlock.append(tmp);
        }
      }else if(row.is_cancelled=='1'){
        orderBlock.append('<p><span class="fL cB" name="myRating">'+t.AchatAnnuleLe[j]+'</span>'+'<span class="fR">'+row.date_updated+'</span></p>')
      }else{
        if(j==0){
          orderBlock.append($('<form id="form-'+row.id+'" action="'+t.UrlBPF[j]+'" method="POST" />').append($('<input type="hidden" name="item" />').val(JSON.stringify(item))).append('<p><span class="fL cB"><span id="ui-info"></span><input type="submit" value="'+t.EffectuerPaiement[j]+'" /></span></p>'));
        }else{
          orderBlock.append($('<form id="form-'+row.id+'" action="'+t.UrlBPF[j]+'/'+row.id+'/'+row.id_seller+'" method="POST" />').append('<p><span class="fL cB"><span id="ui-info"></span>'+'<input id="confirm_sale_button_'+row.id+'" type="button" value="'+t.EffectuerPaiement[j]+'" /></span></p>'));
        }

        orderBlock.append($('<form id="form-'+row.id+'" action="'+t.UrlPA[j]+'/'+row.id+'/'+row.id_seller+'" method="POST" />').append($('<input type="hidden" name="item" />').val(JSON.stringify(item))).append('<input type="hidden" name="answer" value="1"/>').append('<p><span class="fL cB"><span id="ui-info"></span><input type="button" id="'+data[j].cancelShopping+row.id+'" value="'+t.AnnulerCommande[j]+'" /></span></p>'));
      }
    
      $(data[j].accordeon).append(h3).append(div.append(infoBlock).append(orderBlock).append(ratingBlock));
    }
  
    for(var z in data[j].rating){
      var rating=data[j].rating[z];
      $(data[j].accordeon_rating).append('<h3><a href="#">'+rating.date_comment+' - '+rating.pseudo+'</a></h3><div><p>'+rating.comment+'</p><p>'+rating.score+'%</p></div>');
    }
  }
};