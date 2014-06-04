//Пример обработки запроса API 

//Простая аутенфикация

ar User = require('../models/user').User;
var DataProcessError = require('../error').DataProcessError;
var async = require('async');

exports.hello = function(req, res) {
	var ip = req.headers['x-forwarded-for'] || 
	req.connection.remoteAddress || 
	req.socket.remoteAddress ||
	req.connection.socket.remoteAddress;

	var client = {
		"address" : ip,
		"port" : req.client._peername.port,
		"online": true
	}

	var userIn = '';

	req.on('readable', function() {
		userIn = userIn + req.read();
	})
	.on('end', function() {
		userIn = JSON.parse(userIn);
		if (userIn.username && userIn.password) {
			async.waterfall([
				function(callback) {
					User.findOne({username: userIn.username}, callback);
				},
				function(user, callback) {
					if (user) {
						if (user.checkPassword(userIn.password)) {
							callback(null,user);
						} else {
							sendError("Wrong password", res);
						}
					} else {
						sendError("User not found", res);
					}
				},
				function(user, callback) {
					updateNet(user.username, client, callback);
				}], 
				function(err, user) {	
					if(err){
						sendError("error", res);
					}else{
						sendUser(user, res);
					}					
				});		
		}
	});	
}

function updateNet(username, netInfo, callback) {
	var sid = Math.floor(Math.random()*10000000000)+'u';
	sid +=  new Date().getTime()+'';
	User.findOneAndUpdate({username: username}, {"netInfo": netInfo, "sid" : sid}, {new: true}, function(err, user){
		if( err ) {
			callback(err, null);
		} else {
			callback(null, user);
		}
	});
}

function sendUser(user, res) {
	var pack = {
		"username": user.username,
		"netInfo": user.netInfo,
		"id": user.id,
		"sid": user.sid
	}
	res.write(JSON.stringify(pack));
	res.end();
}

function sendError(message, res) {
	res.write(JSON.stringify({
		"error": true, 
		"errorMessage": message
	}));
	res.end();
}