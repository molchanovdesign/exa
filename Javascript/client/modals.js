// Вырезка из кода "Meet You":

//Управление всплывающими окнами и сообщениями в них

var modals = 
{
	_all: [{name: "error"}, {name: "ok"}, {name: "question"}, {name: "notification"}]
	
};

function prepareModals(){
	modals._all.forEach(function(el){
		$('.modal#'+el.name).children('.content').children('button').click(function(){
			el.modalOnBottom();
		});

		el.showModal = function(message){
			modals._all.forEach(function(el){
				el.modalOnBottom();
			});
			this.setMessage(message);
			this.modalOnTop();
		};

		el.modalOnTop = function(){
			$('.modal-wrap#'+this.name).fadeIn(150);
		};

		el.modalOnBottom = function(){
			$('.modal-wrap#'+this.name).hide();
		};

		el.setMessage = function(message){
			$('.modal#'+this.name).children('.content').children('p').html(message);
		}

		el.setButtonCallback = function(callback){
			$('.modal#'+el.name).children('.content').children('button').click(function(){
				el.modalOnBottom();
				callback()
			});
		}

		modals[el.name] = el;
	});
	Object.defineProperty(modals, "_all", {enumerable: false});
}

