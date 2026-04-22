const { v4: uuidv4 } = require("uuid");


module.exports = function requestId(req, res, next) {
  const randomPart = Math.random().toString(16).slice(2, 8);
  const timePart = Date.now().toString(16);

  req.requestId = `req-${timePart}-${randomPart}`;
  console.log(`REQUEST ${req.requestId} ${req.method} ${req.path}`);
  next();
};
