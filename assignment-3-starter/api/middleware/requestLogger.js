const { v4: uuidv4 } = require("uuid");

// TODO: Implement request logging middleware.
// Requirements:
// - Generate a requestId (uuid).
// - Attach it to req.requestId.
// - Log method, path, and requestId to the console (or to a file if you prefer).
// - Later, your error handler should re-use the same requestId in its output.

module.exports = function requestId(req, res, next) {
  const randomPart = Math.random().toString(16).slice(2, 8);
  const timePart = Date.now().toString(16);

  req.requestId = `req-${timePart}-${randomPart}`;
  console.log(`REQUEST ${req.requestId} ${req.method} ${req.path}`);
  next();
};
