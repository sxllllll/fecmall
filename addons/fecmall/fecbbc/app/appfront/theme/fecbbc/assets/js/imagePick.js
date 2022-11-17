/**
 * Created by Administrator on 2016/8/22.
 */
// var ulNode=document.getElementById("smallUl");
// var liNodes=ulNode.getElementsByTagName("li");
var middleDiv=document.getElementById("middleDiv");
//var MiddleImg=middleDiv.getElementsByTagName("img")[0];
var BigImg=document.getElementById("bigImg");
var BigDIV=BigImg.parentNode;


$('.product-detail-page').on('mouseenter', '.thumb-wrap img.thumb', function() {
    tsImgS = $(this).attr("tsImgS");
    $("#bigImg").attr("src", tsImgS);
    $(".img-show").attr("src", tsImgS);
    $(".thumb-wrap img.thumb").removeClass("active");
    $(this).addClass("active");

});
//$('ul').on('mouseleave', 'li', function() {//绑定鼠标划出事件
//    $(this).removeClass('hover');
//});




middleDiv.onmousemove= function (e) {
    var moveIcon=document.getElementById("move-object");//放大镜
    var event=window.event||e;
    var mouseX=event.pageX;//鼠标相对于网页左边的水平距离
    var mouseY=event.pageY;//鼠标相对于网页上边的垂直距离
    var divPosX=this.offsetLeft+this.parentNode.parentNode.offsetLeft;//middleDiv相对于body左边的距离
    var divPosY=this.offsetTop+this.parentNode.parentNode.offsetTop;//middleDiv相对于body上边的距离
    
    var distX=mouseX-divPosX;//鼠标当前位置到middleDiv左边的水平位置
    var distY=mouseY-divPosY;//鼠标当前位置到middleDiv上边的垂直位置
    console.log("鼠标当前位置到0："+divPosX,divPosY);
     console.log("鼠标当前位置到1："+this.offsetLeft, this.offsetTop);
    console.log("鼠标当前位置到2："+this.parentNode.parentNode.offsetLeft, this.parentNode.offsetTop);

    var glassWidth=moveIcon.clientWidth;//放大镜可视宽度
    var glassHeight=moveIcon.clientHeight;//放大镜可视高度
    var middleDivHeight=middleDiv.clientHeight;
    var middleDivWidth=middleDiv.clientWidth;
    console.log("放大镜的宽高："+glassWidth,glassHeight);
    var styleLeft,styleTop;
//    styleLeft=distX-glassWidth/2>0 ? distX-glassWidth/2 :0;
//    styleLeft=distX+glassWidth/2>middleDivWidth ? middleDivWidth-glassWidth : distX-glassWidth/2;

    if(distX-glassWidth/2>0 && distX+glassWidth/2<middleDivWidth ){
        styleLeft=distX-glassWidth/2;

    }else if(distX-glassWidth/2<0){
        styleLeft=0;
    }else if(distX+glassWidth/2>middleDivWidth){
        styleLeft=middleDivWidth-glassWidth;
    }
    if(distY-glassHeight/2>0 && distY+glassHeight/2<middleDivHeight ){
        styleTop=distY-glassHeight/2;

    }else if(distY-glassHeight/2<0){
        styleTop=0;
    }else if(distY+glassHeight/2>middleDivHeight){
        styleTop=middleDivHeight-glassHeight;
    }


    moveIcon.style.top=styleTop+"px";
    moveIcon.style.left=styleLeft+"px";
//    BigImg.style.top=-styleTop*1.8+"px";  //用大图的定位做的
//    BigImg.style.left=-styleLeft*1.8+"px";
    BigDIV.scrollTop=styleTop*1.8;    //用bigDIV的滚动距离做的
    BigDIV.scrollLeft=styleLeft*1.8;
    console.log( BigDIV.scrollTop,BigDIV.scrollLeft)

};
middleDiv.onmouseenter=function(){
    var moveIcon=document.getElementById("move-object");//放大镜
    moveIcon.style.display="block";//移入时放大镜显示
    BigImg.parentNode.style.display="block";
}

middleDiv.onmouseleave= function () {
    var moveIcon=document.getElementById("move-object");//放大镜
    moveIcon.style.display="none";
    BigImg.parentNode.style.display="none";
}