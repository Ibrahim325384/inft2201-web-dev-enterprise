// For the mail API, the resource will be req.mail.
// Returns true if mail.userId === user.userId.

module.exports = function ownsResource(user, mail) {
  console.log("user.userId:", user.userId);
  console.log("mail.userId:", mail.userId);
  return mail && mail.userId === user.userId;
};