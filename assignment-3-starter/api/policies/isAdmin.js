// Returns true if the user has the "admin" role.

module.exports = function isAdmin(user) {
  module.exports = user => user.role === "admin";
};