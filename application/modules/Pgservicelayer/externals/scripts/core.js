/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

(function(){
var $ = 'id' in document ? document.id : window.$;
en4.core.pgservicelayer = {
    authorPhoto: function(author){
        var hoverHtml = "<div class='extfox-widgets' id='extfox-widgets'></div>";
        var authorBadge = '';
        if(author.badgeInfo.hasOwnProperty("count")){
            authorBadge = "<div class='statistic "+ author.badgeInfo.gear +" circle-badge position-absolute "+  author.badgeInfo.class + " d-flex justify-content-center align-items-center text-white'> " +  author.badgeInfo.count  +"</div>";
        }
        var authorHtml = hoverHtml+"<div class='item-photo-guidance position-relative'>"+authorBadge+"<img src='"+author.avatarPhoto.photoURLIcon+"' class='thumb_icon item_photo_user bronze'/></div>";
        return authorHtml;
    },
};
})();
