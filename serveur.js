var WebSocketServer = require("ws").Server;

var serveur = new WebSocketServer({port: 8080});

console.log("Serveur on : port 8080");

serveur.on("connection", function(ws){

  console.log("Nouveau client...");

  ws.on("message", function(str){
    console.log("coucou");
    ws.send("Coucou");
  });

  ws.on("close", function(){
    console.log("Client deconnecte.");
  });

});
