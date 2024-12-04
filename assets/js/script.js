var isSelected = false;
function toggleSelect() {
    if (isSelected == false) {
        $('.form-check-value').each(function(){
            var c = $(this).attr("class");
            $("."+c).attr("checked",true);
        })
        isSelected = true;
        $('#selectButton').val("Deselecionar Todos");
    }
    else {
        $('.form-check-value').each(function(){
            var c = $(this).attr("class");
            $("."+c).attr("checked",false);
        })
        isSelected = false;
        $('#selectButton').val("Selecionar Todos");
    }
}
