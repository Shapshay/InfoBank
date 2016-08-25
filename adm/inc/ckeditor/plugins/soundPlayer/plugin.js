/**
 * Plugin soundPlayer
 * Copyright (c) <2016> <Jacques Malgrange contacter@boiteasite.fr>
 * License MIT
 */
var soundMem=[];
if(typeof soundPlayerUrl==='undefined') var soundPlayerUrl='';

CKEDITOR.plugins.add('soundPlayer',{
	requires:'widget',
	icons:'soundPlayer',
	lang: 'en,es,fr',
	init:function(editor){
		var lang=editor.lang.soundPlayer;
		if(soundPlayerUrl=='')soundPlayerUrl=this.path;
		CKEDITOR.dialog.add('soundPlayerDialog',this.path+'dialogs/soundPlayer.js');
		editor.addContentsCss(this.path+'soundPlayer.css');
		editor.widgets.add('soundPlayer',{
			template:'<div class="soundPlayer"></div>',
			requiredContent:'div(soundPlayer)',
			allowedContent:'div[class](*);span[class](*);ul[class](*)',
			dialog:'soundPlayerDialog',
			upcast:function(e){return e.name=='div'&&e.hasClass('soundPlayer');},
			init:function(){
				soundMem[this.id]=['',''];
				this.setData('one','');
				if(this.element.findOne('a')!=null){
					var a=this.element.getFirst(),b;
					b=a.getAttribute('class');
					if(b.search('compact')!=-1){
						b=a.findOne('li a');
						if(b)soundMem[this.id]=[b.getAttribute('href'),'compact'];
					}else if(b.search('sm2-bar-ui')!=-1){
						b=a.findOne('li a');
						if(b)soundMem[this.id]=[b.getAttribute('href'),'sm2-bar-ui'];
					}else{
						if(a.getName()!='a')a=a.getFirst();
						if(a!=null&&a.getName()=='a')soundMem[this.id]=[a.getAttribute('href'),b];
					}
				}
			},
			data:function(){if(this.data.one!='')this.element.setHtml(this.data.one);}
		});
		editor.ui.addButton('soundPlayer', {
			label:lang.description,
			toolbar:'mytoolbar',
			command:'soundPlayer'
        });
	}
});
