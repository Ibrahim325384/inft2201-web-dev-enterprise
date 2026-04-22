// Very simple in-memory rate limiter for demo purposes.
// Requirements (from assignment spec):
// - Track requests per IP OR per user (token), your choice.
// - Limit to RATE_LIMIT_MAX requests per RATE_LIMIT_WINDOW_SECONDS.
// - When exceeded, produce an error (429 Too Many Requests) via next(err).
// - Include a Retry-After header in the final response (set that in errorHandler).

const windowMs = (parseInt(process.env.RATE_LIMIT_WINDOW_SECONDS, 10) || 60) * 1000;
const maxRequests = parseInt(process.env.RATE_LIMIT_MAX, 10) || 5;

const buckets = new Map();
// shape: key -> { count, windowStart }

module.exports = function rateLimit(req, res, next) {
const max = parseInt(process.env.RATE_LIMIT_MAX) || 5;
  const window = parseInt(process.env.RATE_LIMIT_WINDOW_SECONDS) || 20;

  const key = req.ip;
  const now = Date.now();

  // if key doesn't exist or window has expired, reset it
  if (!buckets.has(key) || (now - buckets.get(key).windowStart) / 1000 > window) {
    buckets.set(key, {
      count: 0,
      windowStart: now
    });
  }

  // increment the count
  const bucket = buckets.get(key);
  bucket.count++;

  // enforce the limit
  if (bucket.count > max) {
    const err = new Error("Too Many Requests");
    err.statusCode = 429;
    err.isOperational = true;
    return next(err);
  }
  
  next();
};