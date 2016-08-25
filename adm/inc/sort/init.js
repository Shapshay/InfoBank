/**
 * Скрипт выполняет настройку сортируемого списка, 
 * формы добавления новых записей, формы изменения порядка записей
 * и кнопок удаления записей.
 *
 * Используются:
 * 1) библиотека jQuery
 * 2) плагин jQuery UI
 * 3) плагин jeditable
 * 4) плагин json-2.2
 *
 * @author Стаценко Владимир
 * @link http://www.simplecoding.org/
 */

$(function() {
	/*
	Эта функция создает элемент списка (тег li) с текстом и иконкой "Удалить" и назначает ему обработчики событий.
	Метод editable подключает плагин jeditable, который делает запись редактируемой.
	Обработчик события click назначается ссылке "Удалить".
	*/
	function createLi(id, note) {
		var note = $('<span class="note" id="n_' + id + '">' + note + '</span>').editable('update.php', editableOptions);
		var link = $('<a href="#"><img src="images/delete.png" alt="Удалить" /></a>').click(removeLi);
		return $('<li id="note_' + id + '" class="editable"></li>').append(note).append(link);
	}
	
	/*
	Создаёт форму с кнопкой "Сохранить сортировку".
	Форма добавляется на страницу если пользователь создает запись в пустом списке, и
	убирается когда пользователь удаляет последнюю запись в списке.
	*/
	function createChangeOrderForm() {
		var form = '<form id="changeOrder" method="post" action="changeorder.php">'
			+ '<p><input type="submit" value="Сохранить сортировку" /></p>'
			+ '</form>';
		//назначаем обработчик событию отправки формы
		form = $(form).submit(updateOrder);
		return form;
	}
	
	/*
	Функция удаления записи из списка.
	Выводит окно с запросом подтверждения и отправляет ajax запрос с id записи, которую нужно удалить.
	Также выполняется проверка количества оставшихся записей в списке.
	*/
	function removeLi() {
		//определяем id текущей записи
		var noteId = $(this).parent().attr('id');
		//запрашиваем подтверждение
		if (confirm('Точно удалить?')) {
			//отправляем запрос
			$.post('removenote.php', {'id':noteId}, function(data) {
				var response = eval('('+data+')');
				if (response['err'] != null) {
					alert(response['err']);
				} else {
					$('#' + response['id']).detach();
					//проверяем, остались ли записи в списке
					var notes = $('#sortable li');
					if (notes.length == 0) {
						//удаляем сам список и форму сортировки записей
						$('#sortable').detach();
						$('#changeOrder').detach();
						//добавляем сообщение "Записи отсутствуют"
						$('<h2>Записи отстутствуют</h2>').insertAfter('#addNewNote');
					}
				}
			});
		}
		return false;
	}
	
	/*
	Функция отправки запроса на сохранение нового порядка записей.
	Формирует массив с элементами вида
	{
		'id':'id записи',
		'order': порядковый_номер_записи_в_отсортированном_списке
	}
	*/
	function updateOrder() {
		var notes = $('ul#sortable li');
		//проверяем количество записей в списке
		if (notes.length > 0) {
			var order = [];
			//формируем массив
			//просматриваем список в обратном порядке, т.к. записи выводятся в порядке
			//убывания значений в поле note_order (это позволяет при создании записи
			//присваивать ей максимальный порядковый номер и размещать в начале списка).
			$.each(notes.get(), function(index, value) {
				order.push({'id': $(value).attr('id'), 'order': index});
			});
			//отправка запроса (метод toJSON добавлен плагином json-2.2)
			$.post('modules/sort/changeorder.php', {'neworder':$.toJSON(order)}, function(data) {
				var response = eval('('+data+')');
				if (response.status == 'OK') {
					$('ul#sortable').effect("highlight", {}, 3000);
				}
				else {
					//alert(response.mes);
				}
			});
		}
		return false;
	}

	//делаем список сортируемым (используется jQuery UI)
	$('#sortable').sortable();
	
	//назначаем обработчик всем ссылкам "Удалить"
	$('#sortable li a').click(removeLi);
	
	//назначаем обработчик форме "Сохранить сортировку"
	$('#changeOrder').submit(updateOrder);
	
	//настройки плагина jeditable
	//используется двойной клик, т.к. одинарный используется для перетягивания элементов
	/*
	var editableOptions = {
		indicator : 'Сохраняю...',
		tooltip : 'Сделайте двойной клик, чтобы изменить текст',
		event : 'dblclick',
		cancel : 'Отмена',
        submit : 'Сохранить'
	};
	
	//делаем поля списка редактируемыми
	$('span.note').editable('update.php', editableOptions);
	*/
	/*
	Функция добавления новой записи.
	Выполняется проверка данных, введенных в форму, и отправка их на сервер.
	Если список на странице отсутствует (записи были удалены ранее), то создаётся новый список
	со всеми обработчиками.
	
	$('#addNewNote').submit(function() {
		var note = $('#notetext').val();
		if (note != '') {
			//отправка запроса
			$.post('addnew.php', {'notetext':note}, function(data) {
				var response = eval('('+data+')');
				if (response['err'] != null) {
					alert(response['err']);
				} else {
					var list = $('#sortable');
					//если список отсутствует
					if (list.length == 0) {
						//удаляем текст "Записи отстутствуют"
						$('h2').detach();
						//создаем список
						$('<ul id="sortable"></ul>').sortable().insertAfter($('#addNewNote'));
						//создаем форму "Сохранить сортировку"
						createChangeOrderForm().insertAfter($('ul#sortable'));
					}
					//вставляем новую запись в список
					createLi(response['id'], response['note']).prependTo('#sortable');
				}
			});
		} else {
			alert('Вы забыли ввести текст заметки');
		}
		return false;
	});
	*/
});