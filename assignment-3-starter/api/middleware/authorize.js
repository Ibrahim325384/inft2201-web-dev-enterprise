// Generic authorization middleware that accepts a policy function.
// The policy function will receive (user, resource) and must return true/false.

module.exports = function authorize(policy) {
  return (req, res, next) => {
    if (policy(req.user, req.mail)) return next();
    const err = new Error("Forbidden");
    err.statusCode = 403;
    err.isOperational = true;
    next(err);;
  };
};