const jwt = require("jsonwebtoken");

const SECRET = process.env.JWT_SECRET || "SECRET_OF_SOME_SORT";


module.exports = function authenticateJWT(req, res, next) {
  const header = req.headers.authorization;

  if (!header) {
    const err = new Error("Missing Authorization header");
    err.statusCode = 401;
    return next(err);
  }

  const token = header.split(" ")[1];

  try {
    req.user = jwt.verify(token, SECRET);
    next();
  } catch (err) {
    err.statusCode = 401;
    next(err);
  }
};