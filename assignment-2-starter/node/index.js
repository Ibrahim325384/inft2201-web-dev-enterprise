import http from "http";
import fs from "fs";
import jwt from "jsonwebtoken";

const JWT_SECRET = "SECRET_STRING";

http
  .createServer((req, res) => {
    if (req.method === "GET") {
      res.writeHead(200, { "Content-Type": "text/plain" });
      res.end("Hello Apache!\n");

      return;
    }

    if (req.method === "POST") {
      if (req.url === "/login") {
        let body = "";
        req.on("data", (chunk) => {
          body += chunk;
        });
        req.on("end", () => {
          try {
            body = JSON.parse(body);

            // handle a login attempt
            

            // open up our "database" (actually a flat file called ./users.txt)
            // to see if there is a username/password combination that matches
            // body.username and body.password
            const fileContent = fs.readFileSync('users.txt', 'utf8');
            const lines = fileContent.split('\n');

            return users.some(line => {
            const [storedUser, storedPass] = line.trim().split(':');
            return storedUser === username && storedPass === password;
            });


            jwt.sign(payload, "<YOUR_RANDOM_SECRET>", { expiresIn: "1h" })

            // return a 404 error if the username isn't found
            res.writeHead(404, { "Content-Type": "text/plain" });
            res.end(`${body.username} not found\n`);

            // return a 401 error if the username is found but the password doesn't match
            res.writeHead(401, { "Content-Type": "text/plain" });
            res.end(`${body.username} incorrect password\n`);

            // on success, return an encoded userId and role using your JWT_SECRET.
            // https://www.npmjs.com/package/jsonwebtoken
          } catch (err) {
            console.log(err);
            res.writeHead(500, { "Content-Type": "text/plain" });
            res.end("Server error\n");
          }
        });
      }

      return;
    }

    res.writeHead(404, { "Content-Type": "text/plain" });
    res.end("Not found\n");
  })
  .listen(8000);

console.log("listening on port 8000");



