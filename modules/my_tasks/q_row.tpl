<div class="q_div" id="q_div{Q_ID}">
    <form>
        <fieldset>
            <h6>{Q_TITLE}</h6>
            <div class="notification success png_bg" style="display: none;" id="corect_a_div{Q_ID}">
                <div id="corect_a{Q_ID}">
                    Ответ
                </div>
            </div>
            <div class="notification information png_bg" style="display: none;" id="hint_q_div{Q_ID}">
                <div id="hint_q{Q_ID}">
                    Подсказка
                </div>
            </div>
            <div id="answers{Q_ID}">
            {ANSWERS}
            <input type="hidden" id="q_id" value="{Q_ID}">
            <p><input type="button" value="Ответить" class="button" onclick="QuestCheck({Q_ID},{COMP_ID},{TASK_ID},{ART_ID});"></p>
            </div>
        </fieldset>
    </form>
</div>

