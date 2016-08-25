/**
 * Plugin soundPlayer
 * Copyright (c) <2016> <Jacques Malgrange contacter@boiteasite.fr>
 * License MIT
 */
CKEDITOR.dialog.add('soundPlayerDialog',function(editor){
	var lang=editor.lang.soundPlayer,a,b,out='';
	var soundButton=function(s,i){
	if(s.src.length<5&&s.src.search(/mp3/i)==-1)return'???';
		a=0;
		b='<div class="sm2-bar-ui[[compact]]"><div class="bd sm2-main-controls"><div class="sm2-inline-texture"></div><div class="sm2-inline-gradient"></div><div class="sm2-inline-element sm2-button-element"><div class="sm2-button-bd"><a href="#play" class="sm2-inline-button play-pause">'+lang.playpause+'</a></div></div><div class="sm2-inline-element sm2-inline-status"><div class="sm2-playlist"><div class="sm2-playlist-target"></div></div><div class="sm2-progress"><div class="sm2-row"><div class="sm2-inline-time">0:00</div><div class="sm2-progress-bd"><div class="sm2-progress-track"><div class="sm2-progress-bar"></div><div class="sm2-progress-ball"><div class="icon-overlay"></div></div></div></div><div class="sm2-inline-duration">0:00</div></div></div></div><div class="sm2-inline-element sm2-button-element sm2-volume"><div class="sm2-button-bd"><span class="sm2-inline-button sm2-volume-control volume-shade"></span><a href="#volume" class="sm2-inline-button sm2-volume-control">'+lang.volume+'</a></div></div>[[menu]]</div><div class="bd sm2-playlist-drawer sm2-element"><div class="sm2-inline-texture"><div class="sm2-box-shadow"></div></div><div class="sm2-playlist-wrapper"><ul class="sm2-playlist-bd">[[list]]</ul></div>[[nextprev]]</div></div>';
		if(s.type==lang.type1){
			out='<div class="ui360"><a href="'+s.src+'" title="'+s.title+'">&nbsp;</a></div>';
			a='ui360';
		}else if(s.type==lang.type2){
			out='<a class="sm2_button" href="'+s.src+'" title="'+s.title+'">&nbsp;</a>';
			a='sm2_button';
		}else if(s.type==lang.type3){
			out='<a class="inline-playable" href="'+s.src+'">'+s.nom+'</a>';
			a='inline-playable';
		}else if(s.type==lang.type4){
			out=b.replace('[[compact]]',' compact').replace('[[menu]]','').replace('[[list]]','<li><a href="'+s.src+'">'+s.nom+'</a></li>').replace('[[nextprev]]','');
			a='compact';
		}else if(s.type==lang.type5){
			out=b.replace('[[compact]]','').replace('[[menu]]','<div class="sm2-inline-element sm2-button-element sm2-menu"><div class="sm2-button-bd"><a href="#menu" class="sm2-inline-button menu">'+lang.menu+'</a></div></div>').replace('[[list]]','<li><a href="'+s.src+'">'+s.nom+'</a></li>').replace('[[nextprev]]','<div class="sm2-extra-controls"><div class="bd"><div class="sm2-inline-element sm2-button-element"><a href="#prev" title="Previous" class="sm2-inline-button previous">&lt;'+lang.prev+'</a></div><div class="sm2-inline-element sm2-button-element"><a href="#next" title="Next" class="sm2-inline-button next">&gt;'+lang.next+'</a></div></div></div>');
			a='sm2-bar-ui';
		}
		if(a!=0)soundMem[i]=[s.src,a];
		return out;
	};
	return{
		title:lang.title,
		minWidth:280,
		minHeight:200,
		contents:[{
			id:'sp0',
			label:'',
			title:'',
			expand:false,
			padding:0,
			elements:[
			{
				type:'select',
				id:'type',
				label:lang.type,
				labelStyle:'line-height:1.6em;',
				style:'display:inline;',
				items:[[lang.type1],[lang.type2],[lang.type3],[lang.type4]], // ,[lang.type5] playlist later
				onChange:function(){
					a=this.getValue();
					b=document.getElementById("soundPlayerImg").style;
					if(a==lang.type1)b.backgroundImage='url("'+soundPlayerUrl+'icons/player360.png")';
					else if(a==lang.type2)b.backgroundImage='url("'+soundPlayerUrl+'icons/mp3button.png")';
					else if(a==lang.type3)b.backgroundImage='url("'+soundPlayerUrl+'icons/inlineplayer.png")';
					else if(a==lang.type4||a==lang.type5)b.backgroundImage='url("'+soundPlayerUrl+'icons/fullplayer.png")';
				},
				setup:function(widget){
					a=document.getElementById("soundPlayerImg").style;
					if(soundMem[widget.id][1].search('ui360')!=-1){
						this.setValue(lang.type1);
						a.backgroundImage='url("'+soundPlayerUrl+'icons/player360.png")';
					}else if(soundMem[widget.id][1].search('sm2_button')!=-1){
						this.setValue(lang.type2);
						a.backgroundImage='url("'+soundPlayerUrl+'icons/mp3button.png")';
					}else if(soundMem[widget.id][1].search('inline-playable')!=-1){
						this.setValue(lang.type3);
						a.backgroundImage='url("'+soundPlayerUrl+'icons/inlineplayer.png")';
					}else if(soundMem[widget.id][1].search('compact')!=-1){
						this.setValue(lang.type4);
						a.backgroundImage='url("'+soundPlayerUrl+'icons/fullplayer.png")';
					}else if(soundMem[widget.id][1].search('sm2-bar-ui')!=-1){
						this.setValue(lang.type5);
						a.backgroundImage='url("'+soundPlayerUrl+'icons/fullplayer.png")';
					}
				},
			},{
				type:'html',
				style:'float:right;margin-top:-40px;height:64px;width:128px;background:transparent url('+soundPlayerUrl+'icons/player360.png) no-repeat scroll center;',
				html:'<div id="soundPlayerImg">&nbsp;</div><div style="clear:both;">&nbsp;</div><img style="display:none" src="'+soundPlayerUrl+'icons/mp3button.png"/><img style="display:none" src="'+soundPlayerUrl+'icons/inlineplayer.png"/><img style="display:none" src="'+soundPlayerUrl+'icons/fullplayer.png"/>'
			},{
				type:'text',
				id:'src0',
				label:lang.title,
				commit:function(widget){
					soundData.src=this.getValue();
					a=soundData.src.split('/');
					soundData.title=a[a.length-1];
					soundData.nom=a[a.length-1].substr(0,a[a.length-1].length-4);
					soundData.type=CKEDITOR.dialog.getCurrent().getContentElement('sp0','type').getValue();
					b=soundButton(soundData,widget.id);
					widget.setData('one',b);
					},
				setup:function(widget){this.setValue(soundMem[widget.id][0]);}
			},{
				type:'button',
				id:'bro',
				style:'display:inline-block;margin-top:10px;',
				filebrowser:
					{
					action:'Browse',
					target:'src0',
					url:editor.config.filebrowserAudioBrowseUrl||editor.config.filebrowserBrowseUrl,
					onSelect:function(fileUrl,data){
						this.getDialog().getContentElement('sp0','src0').setValue(fileUrl);
						return false;
						}
					},
				label:editor.lang.common.browseServer
			}]
		}],
		onShow:function(){soundData={};}
	};
});
