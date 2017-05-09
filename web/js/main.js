$( document ).ready(function(){

    $(".button-collapse").sideNav();
    $('select').material_select();
    $('#deleteModal').modal();

    $(".deleteButton").click(function(e){
        const categoryId    = $(this).data("id");
        const categoryName = $(this).parents('tr').find('td:first').html();
        let href = $("#deleteHref").attr("href");
        let tmpArray = href.split("/");
        tmpArray[tmpArray.length - 1] = categoryId;
        href = tmpArray.join("/");
        $("#deleteHref").attr("href", href);
        $("#deletionNameTarget").html(categoryName);
        $('#deleteModal').modal('open');
    });

});
