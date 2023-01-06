function guidGenerator() {
    var S4 = function() {
       return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
    };
    return (S4()+S4()+"-"+S4()+"-"+S4()+"-"+S4()+"-"+S4()+S4()+S4());
}

$(function() {
  var uniqids = [];
  for (var i = 0; i < 6; i++)
  {
    uniqids[i] = guidGenerator(); //random id generated
  }
  var truee = uniqids[Math.floor(Math.random()*uniqids.length)]; //choice random id from ids array

  var strings = [];
  var imgs = [];
  for (var i = 0; i < uniqids.length; i++)
  {
    let ii = i+1;
    imgs[uniqids[i]] = '<img src="public/imgs/captcha_imgs/'+ii+'.jpg" style="width:5rem;" />';
    //console.log(imgs[uniqids[i]]);
    strings[i] = '<input id="captcha_'+uniqids[i]+'" class="captcha" name="dada" value="'+ii+'" type="radio" />\
    <label class="captcha_img" for="captcha_'+uniqids[i]+'">\
    <img src="public/imgs/captcha_imgs/'+ii+'.jpg" id="img_'+uniqids[i]+'"/>\
    </label>';
  }

  $('.capcha .imgs').before('<div><p>Выберите, пожалуйста, среди других этот рисунок:</p>\
                    <p>'+imgs[truee]+'</p></div>');

  for (var i = 0; i < strings.length; i++)
  {
    $('.capcha .imgs').append(strings[i]);
  }

  $("#img_"+truee).addClass('access');

  $('.capcha .imgs').after('<div><small>После выбора рисунка нажмите Log in.</small></div>');

  $('button.sub').click(function(event){
    let check = $("#captcha_"+truee).is(':checked');
    if ($('#password').val())
    {
      if ( check == true )
      {
        $('form#login_form').submit();
      }
      else
      {
        event.preventDefault();
        alert('Выберите, пожалуйста, соответствующий рисунок :)');
      }
    }
    else
    {
      event.preventDefault();
      alert('Вы забыли ввести пароль :)');
    }
  });
});
