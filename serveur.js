var WebSocketServer = require("ws").Server;
var mysql = require('mysql');

var mySqlClient = mysql.createConnection({
	host : "localhost",
	user : "i_spy",
	password : "asn_hmin205_2017",
	database : "i_spy",
	port : "10000"
});

var connexionPhone = function(login, password){
	return 'SELECT * FROM phone WHERE login = "' + login + '" AND password = "' + password + '";';
}

var connexionUser = function(mail, password){
	return 'SELECT * FROM user WHERE mail = "' + mail + '" AND password = "' + password + '";';
}

var phonesOfUser = function(id){
	return 'SELECT up.phone_id FROM user u JOIN user_phone up ON up.user_id = u.id WHERE u.id = ' + id + ';';
};

var usersOfPhone = function(id){
	return 'SELECT up.user_id FROM phone p JOIN user_phone up ON up.phone_id = p.id WHERE p.id = ' + id + ';';
};

var port = 8080;

var serveur = new WebSocketServer({port: port});

var masters = [];
var slaves = [];

console.log("Serveur on : port " + port);

serveur.on("connection", function(ws){

  console.log("Nouveau client...");
  var prop;
  var type;
  var id;
  var connected = false;
  var myMastersCo = [];
  var myPhonesCo = [];

  ws.on("message", function(str){
  	console.log(str);

    objRecv = JSON.parse(str);

	if(objRecv.cmd == "CONNEXION"){

		prop = JSON.parse(objRecv.prop);
    	type = objRecv.type;
    	id = prop.id;
    	prop.ws = ws;

		if(objRecv.type == "slave"){

			mySqlClient.query(connexionPhone(prop.login, prop.password), function(error, results, fields){
				if(error){
					console.log(error); 
					mySqlClient.end();
					return;
				}
				if(results.length == 1){
					console.log("Connexion phone : OK");
					connected = true;
					slaves.push(prop);
					mySqlClient.query(usersOfPhone(id), function(error, results, fields){
						if(error){
							console.log(error);
							mySqlClient.end();
							return;
						}
						if(results.length > 0){
							for(var num in results){
								for(var num2 in masters){
									if(masters[num2].id == results[num]['user_id']){
										myMastersCo.push(masters[num2]);
										objSend = {
											cmd : "SLAVE_CONNECT",
											id : id,
											login : prop.login
										};
										masters[num2].ws.send(JSON.stringify(objSend));
										console.log("msg send to user " + masters[num2].id + " : phone " + id + " connect");
									}
								}
							}
						}
						else{
							console.log("Recuperation users of phone : pas de resultat");
						}
					});
				}
				else{
					console.log("Connexion phone : pas de resultat");
				}
			});
		}
		else if(objRecv.type == "master"){

			mySqlClient.query(connexionUser(prop.mail, prop.password), function(error, results, fields){
				if(error){
					console.log(error); 
					mySqlClient.end();
					return;
				}
				if(results.length == 1){
					console.log("Connexion user : OK");
					connected = true;
					masters.push(prop);
					mySqlClient.query(phonesOfUser(id), function(error, results, fields){
						if(error){
							console.log(error);
							mySqlClient.end();
							return;
						}
						if(results.length > 0){
							for(var num in results){
								for(var num2 in slaves){
									if(slaves[num2].id == results[num]['phone_id']){
										myPhonesCo.push(slaves[num2]);
										objSend = {
											cmd : "SLAVE_CONNECT",
											id : slaves[num2].id,
											login : slaves[num2].login
										};
										ws.send(JSON.stringify(objSend));
										console.log("msg send to user " + id + " : phone " + slaves[num2].id + " connect");
									}
								}
							}
						}
						else{
							console.log("Recuperation phones of user : pas de resultat");
						}
					});
				}
				else{
					console.log("Connexion user : pas de resultat");
				}
			});
		}
	}
	else{
		if(connected){
			if(objRecv.cmd == "SLAVE_DISCONNECT"){
				for(var num in myPhonesCo){
					if(myPhonesCo[num]['id'] == objRecv.id){
						myPhonesCo.splice(num);
					}
				}
			}
			else if(objRecv.cmd == "MASTER_DISCONNECT"){
				for(var num in myMastersCo){
					if(myMastersCo[num]['id'] == objRecv.id){
						myMastersCo.splice(num);
					}
				}
			}
		}
		else{
			console.log("Not connected : close websocket");
			ws.close();
		}
	}

  });

  ws.on("close", function(){
  	if(type == "slave"){
  		objSend = {
			cmd : "SLAVE_DISCONNECT",
			id : id,
			login : prop.login
		};
  		for(var num in myMastersCo){
  			myMastersCo[num].ws.send(JSON.stringify(objSend));
  		}
  		for(var num in slaves){
  			if(slaves[num] == prop){
  				slaves.splice(num);
  			}
  		}
  	}
  	else if(type == "master"){
  		bjSend = {
			cmd : "MASTER_DISCONNECT",
			id : id
		};
  		for(var num in myPhonesCo){
  			myPhonesCo[num].ws.send(JSON.stringify(objSend));
  		}
  		for(var num in masters){
  			if(masters[num] == prop){
  				masters.splice(num);
  			}
  		}
  	}
    console.log(type + " " + id + " deconnecte.");
  });

});
