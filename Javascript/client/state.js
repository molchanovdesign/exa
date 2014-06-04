// Вырезка из кода приложения для p2p передачи файлов:

// Переменная состояния, 
// реагирующая на изменения информации из любого места кода
// перерисовкой UI

var STATE = {
	self: {
		_password : '',

		_user: null,
		get user(){
			return this._user;
		},

		set user(user){
			this._user = user;
			if(user.password && user.password.length){
				this._password = user.password;
			}else{
				this._user.password = this._password;
			}			
			if(user.netInfo){
				if(user.netInfo.online != this.status){
					this.status = user.netInfo.online;
				}
			}
			if(user.username){
				$('#username').html(user.username);
			}
		},

		_status: false,
		get status(){return this._status},
		set status(status){			
			if(status){				
				$('#statusOffline').hide();
				$('#statusOnline').html('Online');
				$('#statusOnline').show();

				$('#statusBar').removeClass();
				$('#statusBar').addClass('online');
			}else{
				$('#statusOnline').hide();
				$('#statusOffline').html('Offline');
				$('#statusOffline').show();

				$('#statusBar').removeClass();
				$('#statusBar').addClass('offline');
			}	
			this._status = status;		
		},
	},

	_recentUsers: null,
	get recentUsers(){
		return this._recentUsers;
	},

	set recentUsers(recentUsers){
		this._recentUsers = recentUsers;
		$('#recentUsers').html('');
		recentUsers.forEach(function(user){
			$('#recentUsers').append('<li class="'+(user.status ? 'online' : 'offline')+'"><a href="">'+user.username+'</a><p class="info">'+(user.status ? 'Online' : 'Offline')+'</p></li>');
		});
	},

	_searchResults: null,
	get searchResults(){
		return this._searchResults;
	},

	set searchResults(sr){
		this._searchResults = sr;
		$('#searchResults').html('');
		sr.forEach(function(user){
			$('#searchResults').append('<li class="'+(user.netInfo.online ? 'online' : 'offline')+'"><a href="">'+user.username+'</a><p class="info">'+(user.status ? 'Online' : 'Offline')+'</p></li>');
		});
	},
	connectOptions : {
		hostname: 'pippippip.herokuapp.com',
		port: 80
	}
}

Object.defineProperty(STATE.self, "_status", {enumerable: false});
Object.defineProperty(STATE.self, "_user", {enumerable: false});
Object.defineProperty(STATE.self, "_password", {enumerable: false});
Object.defineProperty(STATE, "_recentUsers", {enumerable: false});
Object.defineProperty(STATE, "_searchResults", {enumerable: false});

