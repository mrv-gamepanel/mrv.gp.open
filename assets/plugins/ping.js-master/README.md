# Ping.js

[![npm](https://img.shields.io/npm/v/ping.js.svg)](https://www.npmjs.com/package/ping.js)
[![npm](https://img.shields.io/npm/dt/ping.js.svg)](https://www.npmjs.com/package/ping.js)

Ping.js is a small and simple Javascript library to ping response times to servers in pure Javascript! This is useful for when you want to display realtime ping times on a status page which are relative to the user.

JS Fiddle Example: http://jsfiddle.net/alfg/sr0tpn4x/

Example in jQuery: http://jsfiddle.net/alfg/xjqbvt2o/

## Usage

* Download the distribution files in `dist` to your project.

```javascript
var p = new Ping();

p.ping("https://github.com", function(err, data) {
  // Also display error if err is returned.
  if (err) {
    console.log("error loading resource")
    data = data + " " + err;
  }
  document.getElementById("ping-github").innerHTML = data;
});
```

or import as a module:

```bash
$ npm install ping.js
```

```javascript
import Ping from 'ping.js';
```

or use the [jsdelivr](https://www.jsdelivr.com) CDN:

```html
<script src="https://cdn.jsdelivr.net/gh/alfg/ping.js@0.2.2/dist/ping.min.js" type="text/javascript"></script>
```

See [demo/react-example](demo/react-example) for an example in React.

## API

```javascript
var p = new Ping(opt);
```

### Ping([options])

Create Ping instance.

#### options
Type: `Object`

`favicon` Override the default `favicon.ico` image location.

`timeout` Optional - Set a timeout in milliseconds.

### p.ping(source, callback)

Creates a ping request to the `source`.

`source` IP address or website URL, including protocol and port (optional). Example: `https://example.com:8080`

`callback(err, ping)` Callback function which returns an error and the response time in milliseconds.


## Development

Install project:
```
git clone https://github.com/alfg/ping.js.git
cd ping.js
npm install
npm install -g grunt-cli
```

Run grunt to build distribution files:
```
grunt
```

Open `demo/index.html` in a browser to test results.

## Notes

Javscript itself doesn't have a native way of sending a "ping", so results may not be completely accurate. Since ajax requests are affected by cross-domain issues (CORS), they are blocked by default. `ping.js` is using a method of loading a favicon.ico image from any host and timing the response time. If the favicon image doesn't exist, an error is returned along with the ping value. If there's a better way to do this in Javascript, feel free to create an issue, or pull request so I can review.

## License

[MIT License](http://alfg.mit-license.org/) © Alfred Gutierrez
