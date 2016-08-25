<script>
$(document).ready(function() {
    $('#stat_table').DataTable( {
        "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "Все"]]
    } );

    $('.checkAll').on('click', function(e) {
        $('.cb-element').attr('checked', $(e.target).prop('checked'));
        if($(e.target).prop('checked')){
            $('.cb-element').prop('checked', true);
        }
    });
} );

function changeComp() {
    var comp_id = $("#comp_id option:selected").val();
    $.post("modules/user_comp/list_comp_u.php", {comp_id:comp_id},
            function(data){
                //alert(data);
                var obj = jQuery.parseJSON(data);
                if(obj.result=='OK'){
                    $('#u_table').html(obj.html);
                }
                else{
                    swal("Ошибка Сервера!", "Объект ненайден !", "error");
                }
            });
}

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
</script>