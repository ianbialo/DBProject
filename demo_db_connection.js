var mysql = require('mysql');

var con = mysql.createConnection({
  port: 3312,
  host: "localhost",
  user: "root",
  password: ""
});

con.connect(function(err) {
  if (err) throw err;
  console.log("Connected!");
});