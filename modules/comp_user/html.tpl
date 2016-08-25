<script>
$(document).ready(function() {
    $('#stat_table').DataTable( {
        "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "Все"]]
    });
});

function addUComp() {
    var comp_id = $("#comp_id option:selected").val();
    var users = new Array();
    var ROOT_ID = {ROOT_ID};
    $('.cb-element').each(function(i,elem) {
        if($(elem).prop('checked')){
            users.push(this.id);
        }
    });
    console.log(users);
    $.post("modules/user_comp/add_comp_u.php", {comp_id:comp_id, users:users, ROOT_ID:ROOT_ID},
            function(data){
                //alert(data);
                var obj = jQuery.parseJSON(data);
                if(obj.result=='OK'){
                    $('#u_table').html(obj.html);
                    swal("Успешно", "Пользователям назначен комплекс задач.", "success");
                }
                else{
                    swal("Ошибка Сервера!", "Объект ненайден !", "error");
                }
            });
}

function edtVal(id){
    $.post("modules/comp_user/item.php", {id:id},
            function(data){
                //alert(data);
                var obj = jQuery.parseJSON(data);
                if(obj.result=='OK'){
                    $('#item_id').val(id);
                    $('#name').text(obj.name);
                    $('#login').text(obj.login);
                    getComplex(id);
                    $('#tab2_link').click();
                }
                else{
                    swal("Ошибка Сервера!", "Объект ненайден !", "error");
                    //alert(data);
                }
            });
}

function getComplex(u_id) {
    $.post("modules/comp_user/list_u_comp.php", {u_id:u_id},
            function(data){
                //alert(data);
                var obj = jQuery.parseJSON(data);
                if(obj.result=='OK'){
                    $('#UserComplex').html(obj.html);
                }
                else{
                    swal("Ошибка Сервера!", "Объект ненайден !", "error");
                }
            });
}

function saveComplex() {
    var u_id = $('#item_id').val();
    var comps = new Array();
    var ROOT_ID = {ROOT_ID};
    $('.cb-element').each(function(i,elem) {
        if($(elem).prop('checked')){
            comps.push(this.id);
        }
    });
    $.post("modules/comp_user/save_u_comp.php", {u_id:u_id, comps:comps, ROOT_ID:ROOT_ID},
            function(data){
                //alert(data);
                var obj = jQuery.parseJSON(data);
                if(obj.result=='OK'){
                    $('#UserComplex').html(obj.html);
                    swal("Успешно", "Пользователю назначены комплексы задач.", "success");
                }
                else{
                    swal("Ошибка Сервера!", "Объект ненайден !", "error");
                }
            });
}
</script>