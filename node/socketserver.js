// import libraries
var express = require('express');
var Redis = require('ioredis');
var log4js = require('log4js');
var https = require('https');
var http = require('http');
var fs = require('fs');

// get the server configuration
var config = require('./serverconfig');
// Get values from server config
var privateKey = fs.readFileSync(config.privateKey, 'utf8');
var certificate = fs.readFileSync(config.certificate, 'utf8');
// Setup Https Options
var options = {
  key: privateKey,
  cert: certificate
};
// configure the Logger
log4js.configure({
  appenders: {
    everything: {
      type: config.loggerType,
      filename: config.loggerFileName,
    }
  },
  categories: {
    default: {
      appenders: [ 'everything' ],
      level: config.logLevel
    }
  }
});
// get a Logger
var logger = log4js.getLogger();
// get the 'app'
var app = express();
app.use(log4js.connectLogger(logger, { level: config.logLevel }));
// setup a server
var server = https.createServer(options, app);
// setup socket.io
var io = require('socket.io')(server);
// setup redis
var redis = new Redis();
// Subscribe to new 'Events' in redis
// redis.psubscribe("*"); // ALL channels
redis.subscribe([
  // array of channel names
  'matchscored',
  'teamsignedin',
  'matchcreated',
  'teamcreated',
  'rulesetcreated',
  'competitioncreated',
  'teamsummonedtotable',
  'matchsenttotable'
]);
// connection logger
io.on('connection', (socket) => {
  logger.debug("SocketIO Connection Detected");
});
// Listen to 'all' messages and broadcast
redis.on('message', (channel, message) => {
  message = JSON.parse(message);
  switch(channel) {
    // do actions for each 'channel'
    case 'teamsignedin':
      io.emit(message.event, channel, message.data);
      logger.debug(message.event, channel, message.data);
      break;
    case 'teamsummonedtotable':
      io.emit(message.event, channel, message.data);
      logger.debug(message.event, channel, message.data);
      break;
    case 'matchsenttotable':
      io.emit(message.event, channel, message.data);
      logger.debug(message.event, channel, message.data);
      break;
    default:
      logger.error(`Unknown Channel: ${channel}`);
  }
});
// Start listening for connections
server.listen(config.port, config.host, () => {
  console.log("KIPR Socket Server Running!");
  logger.info("KIPR Socket Server Running!");
  logger.info(`Listening on port ${config.port}`);
  logger.info(server.address());
});
