$(document).ready(function(){
    $(document).on("click",".profile-menu",function(){
       let that = $(this);
       $(".profile-menu").removeClass("active");
       $(that).addClass("active");
       $(".profile-content").addClass("hidden");
       $(".breadcrumb-subtitle").text($(that).data("subtitle"));
       $("."+$(that).prop("id")).removeClass("hidden");
    });
});
