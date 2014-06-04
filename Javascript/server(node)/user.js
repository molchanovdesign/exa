//Работа с базой данных

//организация коллекции пользователей
//описание методов обработки пользователя

var mongoose = require('mongoose'),
	Schema = mongoose.Schema;
var crypto = require('crypto');

var schema = new Schema({
	"username" : {
		type: String,
		unique: true,
		required: true
	},

	"hashedPassword" : {
		type: String,
		required: true
	},

	"salt": {
		type: String,
		required: true
	},

	"netInfo": {
		type: Object,
	}
});

schema.methods.encryptPassword = function(password) {
	return crypto.createHmac("sha256", this.salt).update(password).digest('hex');
};

schema.virtual('password')
	  .set(function(password){
	  		this._plainPassword = password;
	  		this.hashedPassword = this.encryptPassword(password);
	  })
	  .get(function(){
	  		return this._plainPassword;
	  });

schema.methods.checkPassword = function(password) {
	return this.encryptPassword(password) === this.hashedPassword;
};

exports.User = mongoose.model('User', schema);

