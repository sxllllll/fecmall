$(document).ready(function(){
    currentBaseUrl = $(".currentBaseUrl").val();
    $(".top_currency ul.language-currency-list  li").click(function(){
        currency = $(this).attr("rel");

        htmlobj=$.ajax({url:currentBaseUrl+"/cms/home/changecurrency?currency="+currency,async:false});
        //alert(htmlobj.responseText);
        location.reload() ;
    });
    $(".top_lang .store_lang").click(function(){ 
        //http = document.location.protocol+"://";
        currentStore = $(".current_lang").attr("rel");
        changeStore = $(this).attr("rel");
        currentUrl = window.location.href;
        redirectUrl = currentUrl.replace("://"+currentStore,"://"+changeStore);
        //alert(redirectUrl);
        //alert(2);
        location.href=redirectUrl;
    });
    
    $(".myyoho").hover(function(){
       
        $(this).addClass("myyoho-hover");
        
    },function(){
       
        $(this).removeClass("myyoho-hover");
        
    });
    
    
    // right-floating-layer
    
    window.onload = function(){
        var screenw = document.documentElement.clientWidth || document.body.clientWidth;
        var screenh = document.documentElement.clientHeight || document.body.clientHeight;
        var screenb = $(document).height(); 
        window.onscroll = function(){
            var scrolltop = document.documentElement.scrollTop || document.body.scrollTop;
            if(scrolltop-10 > 0){
                $("#goTop").removeClass('hide');
                
            }else{
                $("#goTop").addClass('hide');
            }
            
        }
        $("#goTop").click(function(){
            $("html,body").animate({scrollTop:0},"slow");
        });
        
    }  	
                
                

    // ajax get account login info

    loginInfoUrl = currentBaseUrl+"/customer/ajax";
    logoutUrl 	 = $(".logoutUrl").val();
    product_id   = $(".product_view_id").val();
    product_id	 = product_id ? product_id : null;
    $.ajax({
        async:true,
        timeout: 6000,
        dataType: 'json',
        type:'get',
        data: {
            'currentUrl':window.location.href,
            'product_id':product_id
        },
        url:loginInfoUrl,
        success:function(data, textStatus){
            welcome = $('.welcome_str').val();
            logoutStr = $('.logoutStr').val();
            if(data.loginStatus){
                customer_name = data.customer_name;
                str = welcome+' '+customer_name;
                //str += '<span id="js_isNotLogin">';
                // str += '&nbsp; <a href="'+logoutUrl+'" rel="nofollow">'+logoutStr+'</a>&nbsp; ';
                //str += '</span>';
                str += '[ <a id="reg-url" href="'+logoutUrl+'" class="registbar" rel="nofollow">'+logoutStr+'</a> ]';
            
                $("#loginBox").html(str);
                
                $(".simple-user-center").removeClass("hide");
                $(".user-name a").html(customer_name);
            }
            if(data.favorite){
                //$(".des_share .d_care").addClass("act");
                $(".collect-product").addClass('coled');
                $val = $(".favorited").val();
                $(".collect-product em").html($val);
            }
            if(data.favorite_product_count){
                $("#js_favour_num").html(data.favorite_product_count);
            }
            if(data.csrfName && data.csrfVal && data.product_id){
                $(".product_csrf").attr("name",data.csrfName);
                $(".product_csrf").val(data.csrfVal);
            }
            if(data.cart_qty){
                $(".goods-num-tip").html(data.cart_qty);
            }

        },
        error:function (XMLHttpRequest, textStatus, errorThrown){}
    });
    var mouseout = true;
    $(".i_car").on("mouseenter", function (event) {
        if (mouseout == true) {
            mouseout = false;
            $(".cart-loading").show();
            $(".cart-empty").hide();
            $(".cart-summary-info").hide();
            $(".cart-summary-total").hide();
            var cartInfoUrl = currentBaseUrl+"/checkout/cartinfo/index";
            $.ajax({
                async: true,
                timeout: 6000,
                dataType: 'json',
                type: 'get',
                url: cartInfoUrl,
                success: function(data, textStatus){
                    $(".cart-loading").hide();
                    var product_total = data.product_total;
                    var cart_items = '';
                    if (parseFloat(product_total)) {
                        $(".cart-summary-info").show();
                        $(".cart-summary-total").show();
                        var c_symbol = data.symbol;
                        var products = data.products;
                        for (var x in products) {
                            product = products[x];
                            product_image = product['product_image'];
                            name = product['name'];
                            product_price = product['product_price'];
                            product_url = product['product_url'];
                            qty = product['qty'];
                            custom_option_info = product['custom_option_info'];
                            
                            cart_items += '<li>';
                            cart_items += '    <div class="img"><a href="'+ product_url +'"><img src="'+ product_image +'" width="58" height="58" /></a></div>';
                            cart_items += '    <div class="name"><a href="'+ product_url +'">'+ name +'</a></div>';
                            cart_items += '    <div class="price"><font color="#ff4e00">'+ c_symbol + product_price + '</font> X '+ qty +'</div>';
                            cart_items += '</li>';
                
                            
                        }
                        
                        $(".cart-summary-info").html(cart_items);
                        $(".top_cart_total").html(c_symbol + product_total);
                    } else {
                        
                        $(".cart-empty").show();
                    }
                    
                    

                },
                error:function (XMLHttpRequest, textStatus, errorThrown){
                    $(".cart-loading").hide();
                    $(".cart-empty").show();
                }
            });
        }    
        
        

	});
    // 顶部菜单部分
    $(".tool-options").hover(function(){
        $(".tool-options  .tool-select").show();
    },function(){
        $(".tool-options  .tool-select").hide();
    });

	$(".minicart-section").on("mouseleave", function () {
		mouseout = true;
        $("#cart-floating-box").hide();
	});

});

function doPost(to, p) { // to:提交动作（action）,p:参数
    var myForm = document.createElement("form");
    myForm.method = "post";
    myForm.action = to;
    for (var i in p){
        var myInput = document.createElement("input");
        myInput.setAttribute("name", i); // 为input对象设置name
        myInput.setAttribute("value", p[i]); // 为input对象设置value
        myForm.appendChild(myInput);
    }
    document.body.appendChild(myForm);
    myForm.submit();
    document.body.removeChild(myForm); // 提交后移除创建的form
}

