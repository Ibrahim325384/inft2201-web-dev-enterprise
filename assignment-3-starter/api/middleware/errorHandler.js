// Centralized error handler.
// This should be the LAST app.use(...) in server.js.

module.exports = function errorHandler(err, req, res, next) {
  
  const requestId = req.requestId || "unknown";
  const timestamp = new Date().toISOString();

  let statusCode = 500;
  let error = "Internal Server Error";
  let message = "An unexpected error occurred.";

  if (err.isOperational) {
    statusCode = err.statusCode || 400;

    if (statusCode === 400) error = "Bad Request";
    else if (statusCode === 404) error = "Not Found";
    else if (statusCode === 403) error = "Forbidden";
    else if (statusCode === 429) error = "Too Many Requests";
    else if (statusCode === 401) error = "Unathorized";

    message = err.message;
  } else {
    // Programmer error: log full details for developers
    console.error("PROGRAMMER ERROR:", {
      requestId,
      message: err.message,
      stack: err.stack
    });
  }

  console.error("Unhandled error for request", req.requestId, err);

  res.status(statusCode).json({
    error,
    message,
    statusCode,
    requestId,
    timestamp
  });
};