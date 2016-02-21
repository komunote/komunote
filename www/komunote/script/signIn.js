check=function(){
  var _email=$('#email').val();
  var _pseudo=$('#pseudo').val();

  $.getScript('ajax.php?c=admin&a=SigninX&p=checkUser&email='+_email+'&pseudo='+_pseudo);
  
  if(_email!='' && _pseudo!=''){

    errorSum=0;
    for(var id in fields){
      $(id).change();
    }
    if(errorSum===0 && codeSecu==='ok')$("#formInscription").submit();
    return true;
  } else {
    
    if (_email == '') {
      showPopup($('#email'), '#infoEmail');
    }    
    if (_pseudo == '') {
      showPopup($('#pseudo'), '#infoPseudo');
    }
  }
  
  return false;
};