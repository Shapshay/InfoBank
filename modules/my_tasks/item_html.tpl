<script>
function QuestCheck(q_id, comp_id, task_id, art_id){
    var radio_name = "q_"+q_id;
    var a_id = $('input[name='+radio_name+']:checked').val();
    var u_id = {ROOT_ID};
    var cookieName = 'task';
    var cookieName2 = 'task_t';
    if(a_id) {
        $.post("modules/my_tasks/check_a.php", {q_id:q_id, a_id:a_id, comp_id:comp_id, task_id:task_id, art_id:art_id, u_id:u_id},
                function(data){
                    //alert(data);
                    var obj = jQuery.parseJSON(data);
                    if(obj.result=='OK'){
                        swal("Успешно", "Правильный ответ.", "success");
                        $('#answers'+q_id).hide();
                        $('#hint_q_div'+q_id).hide();
                        $('#corect_a'+q_id).text(obj.answer);
                        $('#corect_a_div'+q_id).show();

                        if(obj.type_ok!=''){
                            if(obj.type_ok=='close_comp'){
                                $.cookie(cookieName, null, {path:'/'});
                                $.cookie(cookieName2, null, {path:'/'});
                                window.location = '/moi_zadachi';
                            }
                            else{
                                if(obj.type_ok=='close_task'){
                                    $.cookie(cookieName, null, {path:'/'});
                                    $.cookie(cookieName2, null, {path:'/'});
                                }
                                window.location = '/moi_zadachi/?comp='+comp_id;
                            }
                        }
                    }
                    else{
                        if(obj.hint!=''){
                            swal("Ошибка!", "Неверный ответ !\nПрочитайте подсказку.", "error");
                            $('#hint_q'+q_id).text(obj.hint);
                            $('#hint_q_div'+q_id).show();
                        }
                        else{
                            swal("Ошибка!", "Неверный ответ !", "error");
                        }

                    }
                });
    }
    else{
        swal("Ошибка!", "Выберите ответ !", "error");
    }
}
</script>

// timer
<script type="text/javascript" src="js/jquery.cookie.js"></script>

<script>
    $(document).ready(function() {
        var cookieName = 'task';
        var cookieName2 = 'task_t';
        var cookieOptions = {expires: 1, path: '/'};
        var myCanvas;
        var timerText;
        if($.cookie(cookieName2)){
            timerText = $.cookie(cookieName2);
        }
        else{
            timerText = {TASK_TIMER_TEXT};
            $.cookie(cookieName2, {TASK_TIMER_TEXT}, {expires: 1, path: '/'});
        }
        if($.cookie(cookieName)){
            myCanvas = document.getElementById("myCanvas"),
                    context = myCanvas.getContext("2d"),
                    timeLimit = {TASK_TIMER},
                    timeStart = $.cookie(cookieName),
                    canvasSize = 150,
                    lineWidth = 15,
                    drawX = drawY = radius = canvasSize / 2;
        }
        else{
           myCanvas = document.getElementById("myCanvas"),
                    context = myCanvas.getContext("2d"),
                    timeLimit = {TASK_TIMER},
                    timeStart = (new Date).getTime(),
                    canvasSize = 150,
                    lineWidth = 15,
                    drawX = drawY = radius = canvasSize / 2;
        }

        radius -= lineWidth / 2;
        myCanvas.width = canvasSize;
        myCanvas.height = canvasSize;
        function go() {
            context.beginPath();
            context.lineWidth = lineWidth;
            context.lineCap = "round";
            context.strokeStyle = "#65AA00";
            var a = ((new Date).getTime() - timeStart) / timeLimit;
            /*
            console.log('(new Date).getTime() = '+(new Date).getTime());
            console.log('timeStart = '+ timeStart);
            console.log('(new Date).getTime() - timeStart) = '+((new Date).getTime() - timeStart));
            console.log('timeLimit = '+timeLimit);
            console.log('a = '+a);
            */
            if(a>=1){
                swal("Ошибка!", "Время на прохождение задачи закончилось!", "error");
                $.cookie(cookieName, null, {path:'/'});
                $.cookie(cookieName2, null, {path:'/'});
                setTimeout(function(){     window.location = '/moi_zadachi';}, 4000);
            }
            else{
                context.clearRect(0, 0, canvasSize, canvasSize);
                context.font = '30px "Tahoma"';
                context.textAlign = 'center';
                context.fillText((timerText--)|0, drawX, drawY+10);
                $.cookie(cookieName, timeStart, cookieOptions);
                $.cookie(cookieName2, timerText, {expires: 1, path: '/'});
                context.arc(drawX, drawY, radius, -Math.PI / 2 + 2 * Math.PI * a, -Math.PI / 2, !1);
                context.stroke();
                1 < a && (timeStart = (new Date).getTime());
                timer = window.setTimeout(go, 1000);
            }
        }
        go();
    } );

    function ShowArt() {
        $('#HideArtDiv').show();
        $('#HideQuestions').hide();
    }

    function ShowQuest() {
        $('#HideQuestions').show();
        $('#HideArtDiv').hide();
    }
</script>